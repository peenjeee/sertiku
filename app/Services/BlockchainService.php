<?php
namespace App\Services;

use App\Models\Certificate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BlockchainService
{
    protected string $rpcUrl;
    protected string $chainId;
    protected string $privateKey;
    protected string $walletAddress;
    protected string $contractAddress;
    protected bool $enabled;

    public function __construct()
    {
        $this->enabled = config('blockchain.enabled', false);
        $this->rpcUrl = config('blockchain.rpc_url', 'https://rpc-amoy.polygon.technology/');
        $this->chainId = config('blockchain.chain_id', '80002');
        $this->privateKey = config('blockchain.private_key', '');
        $this->walletAddress = config('blockchain.wallet_address', '');
        $this->contractAddress = config('blockchain.contract_address', '');
    }

    /**
     * Check if blockchain is enabled and configured
     */
    public function isEnabled(): bool
    {
        return $this->enabled
            && !empty($this->privateKey)
            && !empty($this->walletAddress);
    }

    /**
     * Get explorer URL for Polygon Amoy (PolygonScan)
     */
    public function getExplorerUrl(string $txHash): string
    {
        return "https://amoy.polygonscan.com/tx/{$txHash}";
    }

    /**
     * Generate SHA-256 hash of certificate data
     * Includes file integrity hashes for verification
     */
    public function generateCertificateHash(Certificate $certificate): string
    {
        $data = json_encode([
            'certificate_number' => $certificate->certificate_number,
            'recipient_name' => $certificate->recipient_name,
            'recipient_email' => $certificate->recipient_email,
            'course_name' => $certificate->course_name,
            'issue_date' => $certificate->issue_date->format('Y-m-d'),
            'issuer_id' => $certificate->user_id,
            'created_at' => $certificate->created_at->format('Y-m-d H:i:s'),
            // Include file integrity hashes
            'file_hashes' => $certificate->getFileHashes(),
        ]);

        return '0x' . hash('sha256', $data);
    }

    /**
     * Store certificate hash on Polygon blockchain
     * Uses Node.js script for transaction signing (more reliable than PHP)
     */
    public function storeCertificateHash(Certificate $certificate): ?string
    {
        if (!$this->isEnabled()) {
            Log::warning('BlockchainService: Blockchain not enabled or not configured');
            return null;
        }

        try {
            // Generate certificate hash
            $certHash = $this->generateCertificateHash($certificate);

            // Try Pure PHP signing first (Low Memory)
            $txHash = $this->signWithPhp($certHash);

            // Fallback to Node.js only if PHP fails (though PHP is preferred)
            if (!$txHash) {
                try {
                    $txHash = $this->signWithNode($certHash);
                } catch (\Exception $e) {
                    Log::warning("NodeJS fallback also failed: " . $e->getMessage());
                }
            }

            if ($txHash) {
                // Update certificate with blockchain info
                $certificate->update([
                    'blockchain_hash' => $certHash,
                    'blockchain_tx_hash' => $txHash,
                    'blockchain_status' => 'confirmed',
                    'blockchain_verified_at' => now(),
                ]);

                Log::info("Certificate {$certificate->certificate_number} stored on blockchain: {$txHash}");

                return $txHash;
            }

            // If all methods fail, still save the hash for reference
            $certificate->update([
                'blockchain_hash' => $certHash,
                'blockchain_status' => 'pending',
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('BlockchainService Error: ' . $e->getMessage());

            $certificate->update([
                'blockchain_status' => 'failed',
            ]);

            return null;
        }
    }

    /**
     * Sign transaction using Node.js script (most reliable method)
     */
    /**
     * Sign transaction using Pure PHP (No Node.js required)
     * Uses kornrunner/ethereum-offline-raw-tx package
     */
    protected function signWithPhp(string $dataHash): ?string
    {
        try {
            // Get nonce
            $nonce = $this->getNonce();
            if (!$nonce) {
                return null;
            }

            // Get gas price
            $gasPrice = $this->getGasPrice();
            if (!$gasPrice) {
                $gasPrice = '0x3B9ACA00'; // 1 Gwei fallback
            }

            // Prepare transaction data
            // Note: We need to convert hex strings to proper format for the library
            $transaction = new \KornRunner\Ethereum\Transaction(
                $nonce,
                $gasPrice,
                '0x6270', // Gas limit ~25200
                $this->walletAddress, // To (self)
                '0x0', // Value
                $dataHash // Data
            );

            // Sign transaction
            $privateKey = $this->privateKey;
            $rawTx = $transaction->getRaw($privateKey, $this->chainId);

            // Broadcast transaction
            $response = Http::timeout(30)->post($this->rpcUrl, [
                'jsonrpc' => '2.0',
                'method' => 'eth_sendRawTransaction',
                'params' => ['0x' . $rawTx],
                'id' => 1,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                if (isset($result['result'])) {
                    return $result['result']; // Returns tx hash
                }

                if (isset($result['error'])) {
                    Log::error('BlockchainService PHP Sign Error: ' . json_encode($result['error']));
                }
            }

            return null;

        } catch (\Exception $e) {
            Log::error('BlockchainService PHP Sign Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Sign transaction using direct RPC call
     * This sends a simple value transfer with data
     */
    protected function signWithDirectRpc(string $dataHash): ?string
    {
        try {
            // Get nonce
            $nonce = $this->getNonce();
            if (!$nonce) {
                return null;
            }

            // Get gas price
            $gasPrice = $this->getGasPrice();
            if (!$gasPrice) {
                $gasPrice = '0x3B9ACA00'; // 1 Gwei
            }

            // Build unsigned transaction
            $txParams = [
                'from' => $this->walletAddress,
                'to' => $this->walletAddress,
                'value' => '0x0',
                'gas' => '0x6270', // ~25200 gas for data
                'gasPrice' => $gasPrice,
                'nonce' => $nonce,
                'data' => $dataHash,
            ];

            // Try eth_sendTransaction (works if wallet is unlocked on node)
            $response = Http::timeout(30)->post($this->rpcUrl, [
                'jsonrpc' => '2.0',
                'method' => 'eth_sendTransaction',
                'params' => [$txParams],
                'id' => 1,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                if (isset($result['result'])) {
                    return $result['result'];
                }
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Direct RPC failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get current nonce for wallet
     */
    public function getNonce(): ?string
    {
        $response = Http::timeout(30)->post($this->rpcUrl, [
            'jsonrpc' => '2.0',
            'method' => 'eth_getTransactionCount',
            'params' => [$this->walletAddress, 'latest'],
            'id' => 1,
        ]);

        if ($response->successful() && isset($response['result'])) {
            return $response['result'];
        }

        return null;
    }

    /**
     * Get current gas price
     */
    public function getGasPrice(): ?string
    {
        $response = Http::timeout(30)->post($this->rpcUrl, [
            'jsonrpc' => '2.0',
            'method' => 'eth_gasPrice',
            'params' => [],
            'id' => 1,
        ]);

        if ($response->successful() && isset($response['result'])) {
            return $response['result'];
        }

        return null;
    }

    /**
     * Check transaction status
     */
    public function getTransactionStatus(string $txHash): ?string
    {
        $response = Http::timeout(30)->post($this->rpcUrl, [
            'jsonrpc' => '2.0',
            'method' => 'eth_getTransactionReceipt',
            'params' => [$txHash],
            'id' => 1,
        ]);

        if ($response->successful() && isset($response['result'])) {
            $receipt = $response['result'];
            if ($receipt === null) {
                return 'pending';
            }
            return $receipt['status'] === '0x1' ? 'confirmed' : 'failed';
        }

        return null;
    }

    /**
     * Verify if a transaction hash exists on blockchain (legacy method)
     */
    public function verifyTransactionOnChain(string $txHash): ?array
    {
        $response = Http::timeout(30)->post($this->rpcUrl, [
            'jsonrpc' => '2.0',
            'method' => 'eth_getTransactionByHash',
            'params' => [$txHash],
            'id' => 1,
        ]);

        if ($response->successful() && isset($response['result'])) {
            $tx = $response['result'];
            if ($tx) {
                return [
                    'hash' => $tx['hash'],
                    'from' => $tx['from'],
                    'to' => $tx['to'],
                    'data' => $tx['input'],
                    'blockNumber' => hexdec($tx['blockNumber']),
                ];
            }
        }

        return null;
    }

    /**
     * Get wallet MATIC balance
     */
    public function getWalletBalance(): ?array
    {
        if (empty($this->walletAddress)) {
            return null;
        }

        try {
            $response = Http::timeout(10)->post($this->rpcUrl, [
                'jsonrpc' => '2.0',
                'method' => 'eth_getBalance',
                'params' => [$this->walletAddress, 'latest'],
                'id' => 1,
            ]);

            if ($response->successful() && isset($response->json()['result'])) {
                $balanceWei = hexdec($response->json()['result']);
                $balanceMatic = $balanceWei / 1e18;

                return [
                    'wei' => $balanceWei,
                    'matic' => round($balanceMatic, 6),
                    'formatted' => number_format($balanceMatic, 4) . ' MATIC',
                ];
            }
        } catch (\Exception $e) {
            Log::error('BlockchainService: Failed to get wallet balance: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Get transaction count (nonce) for wallet
     */
    public function getTransactionCount(): ?int
    {
        if (empty($this->walletAddress)) {
            return null;
        }

        try {
            $response = Http::timeout(10)->post($this->rpcUrl, [
                'jsonrpc' => '2.0',
                'method' => 'eth_getTransactionCount',
                'params' => [$this->walletAddress, 'latest'],
                'id' => 1,
            ]);

            if ($response->successful() && isset($response->json()['result'])) {
                return hexdec($response->json()['result']);
            }
        } catch (\Exception $e) {
            Log::error('BlockchainService: Failed to get transaction count: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Get complete wallet info for dashboard
     */
    public function getWalletInfo(): array
    {
        $balance = $this->getWalletBalance();
        $txCount = $this->getTransactionCount();

        // Estimate remaining certificates based on average gas cost
        $avgGasCostMatic = 0.02; // ~0.02 MATIC per certificate (user confirmed)
        $remainingCerts = $balance ? floor($balance['matic'] / $avgGasCostMatic) : 0;

        return [
            'enabled' => $this->isEnabled(),
            'wallet_address' => $this->walletAddress,
            'short_address' => $this->walletAddress ? substr($this->walletAddress, 0, 6) . '...' . substr($this->walletAddress, -4) : null,
            'balance' => $balance,
            'transaction_count' => $txCount,
            'remaining_certs' => $remainingCerts,
            'network' => $this->chainId === '80002' ? 'Polygon Amoy (Testnet)' : 'Polygon Mainnet',
            'explorer_url' => config('blockchain.explorer_url', 'https://amoy.polygonscan.com'),
            'rpc_url' => $this->rpcUrl,
        ];
    }

    /**
     * Get wallet address
     */
    public function getWalletAddress(): string
    {
        return $this->walletAddress;
    }

    /**
     * Check if smart contract is configured
     */
    public function hasContract(): bool
    {
        return !empty($this->contractAddress);
    }

    /**
     * Get contract address
     */
    public function getContractAddress(): string
    {
        return $this->contractAddress;
    }

    /**
     * Store certificate hash on smart contract
     * Uses Node.js script for contract interaction
     */
    public function storeWithContract(Certificate $certificate): ?string
    {
        if (!$this->isEnabled()) {
            Log::warning('BlockchainService: Blockchain not enabled');
            return null;
        }

        if (!$this->hasContract()) {
            Log::warning('BlockchainService: Smart contract not configured, falling back to data tx');
            return $this->storeCertificateHash($certificate);
        }

        try {
            $certHash = $this->generateCertificateHash($certificate);
            $scriptPath = base_path('scripts/interact_contract.js');

            if (!file_exists($scriptPath)) {
                Log::warning('BlockchainService: interact_contract.js not found');
                return $this->storeCertificateHash($certificate);
            }

            // Prepare certificate data for smart contract
            $hash = escapeshellarg($certHash);
            $certNumber = escapeshellarg($certificate->certificate_number ?? '');
            $recipientName = escapeshellarg($certificate->recipient_name ?? '');
            $courseName = escapeshellarg($certificate->course_name ?? '');
            $issueDate = escapeshellarg($certificate->issue_date?->format('Y-m-d') ?? '');

            // Include PDF file hashes (SHA256 and MD5) in issuerName for visibility on blockchain explorer
            $issuer = $certificate->user->institution_name ?? $certificate->user->name ?? '';

            // Build hash info string with all file hashes (like IPFS metadata)
            $hashParts = [];

            // PDF file hashes (from dompdf)
            if ($certificate->certificate_sha256) {
                $hashParts[] = 'PDF_SHA256:' . $certificate->certificate_sha256;
            }
            if ($certificate->certificate_md5) {
                $hashParts[] = 'PDF_MD5:' . $certificate->certificate_md5;
            }

            // QR code file hashes
            if ($certificate->qr_sha256) {
                $hashParts[] = 'QR_SHA256:' . $certificate->qr_sha256;
            }
            if ($certificate->qr_md5) {
                $hashParts[] = 'QR_MD5:' . $certificate->qr_md5;
            }

            // Template file hashes (if template is used)
            if ($certificate->template) {
                if ($certificate->template->sha256) {
                    $hashParts[] = 'TPL_SHA256:' . $certificate->template->sha256;
                }
                if ($certificate->template->md5) {
                    $hashParts[] = 'TPL_MD5:' . $certificate->template->md5;
                }
            }

            $hashInfo = !empty($hashParts) ? ' | ' . implode(' | ', $hashParts) : '';
            $issuerWithHash = $issuer . $hashInfo;
            $issuerName = escapeshellarg($issuerWithHash);

            // Use full path for hosting, fallback to 'node' for local
            $nodePath = env('NODE_PATH', 'node');

            // Add --max-old-space-size=128 to prevent OOM Killed error on hosting
            $command = "{$nodePath} --max-old-space-size=128 {$scriptPath} store {$hash} {$certNumber} {$recipientName} {$courseName} {$issueDate} {$issuerName} 2>&1";

            Log::info('BlockchainService: Storing certificate via smart contract with full data');

            $output = shell_exec($command);
            $output = trim($output ?? '');

            // Parse last JSON line (the confirmed status)
            $lines = explode("\n", $output);
            $lastLine = end($lines);
            $result = json_decode($lastLine, true);

            if ($result && isset($result['success']) && $result['success']) {
                $txHash = $result['transactionHash'] ?? null;

                if ($txHash) {
                    $certificate->update([
                        'blockchain_hash' => $certHash,
                        'blockchain_tx_hash' => $txHash,
                        'blockchain_status' => $result['status'] ?? 'confirmed',
                        'blockchain_verified_at' => now(),
                    ]);

                    Log::info("Certificate {$certificate->certificate_number} stored on smart contract: {$txHash}");
                    return $txHash;
                }
            }

            Log::error('BlockchainService: Smart contract storage failed: ' . $output);

            // Fallback to data tx
            return $this->storeCertificateHash($certificate);

        } catch (\Exception $e) {
            Log::error('BlockchainService: Smart contract error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Verify certificate on smart contract
     * @return array|null null if not found, array with exists, issuer, timestamp if found
     */
    public function verifyCertificateOnChain(string $certHash): ?array
    {
        if (!$this->hasContract()) {
            return null;
        }

        try {
            $scriptPath = base_path('scripts/interact_contract.js');

            if (!file_exists($scriptPath)) {
                return null;
            }

            $hash = escapeshellarg($certHash);
            $nodePath = env('NODE_PATH', 'node');
            $command = "{$nodePath} {$scriptPath} verify {$hash} 2>&1";

            $output = shell_exec($command);
            $output = trim($output ?? '');

            $result = json_decode($output, true);

            if ($result && isset($result['success']) && $result['success']) {
                return $result;
            }

            return null;

        } catch (\Exception $e) {
            Log::error('BlockchainService: Verify error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get contract statistics
     */
    public function getContractStats(): ?array
    {
        if (!$this->hasContract()) {
            return null;
        }

        try {
            $scriptPath = base_path('scripts/interact_contract.js');

            if (!file_exists($scriptPath)) {
                return null;
            }

            $nodePath = env('NODE_PATH', 'node');
            $command = "{$nodePath} {$scriptPath} stats 2>&1";
            $output = shell_exec($command);
            $output = trim($output ?? '');

            $result = json_decode($output, true);

            if ($result && isset($result['success']) && $result['success']) {
                return $result;
            }

            return null;

        } catch (\Exception $e) {
            Log::error('BlockchainService: Stats error: ' . $e->getMessage());
            return null;
        }
    }
}
