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
    protected bool $enabled;

    public function __construct()
    {
        $this->enabled       = config('blockchain.enabled', false);
        $this->rpcUrl        = config('blockchain.rpc_url', 'https://rpc-amoy.polygon.technology/');
        $this->chainId       = config('blockchain.chain_id', '80002');
        $this->privateKey    = config('blockchain.private_key', '');
        $this->walletAddress = config('blockchain.wallet_address', '');
    }

    /**
     * Check if blockchain is enabled and configured
     */
    public function isEnabled(): bool
    {
        return $this->enabled
        && ! empty($this->privateKey)
        && ! empty($this->walletAddress);
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
     */
    public function generateCertificateHash(Certificate $certificate): string
    {
        $data = json_encode([
            'certificate_number' => $certificate->certificate_number,
            'recipient_name'     => $certificate->recipient_name,
            'recipient_email'    => $certificate->recipient_email,
            'course_name'        => $certificate->course_name,
            'issue_date'         => $certificate->issue_date->format('Y-m-d'),
            'issuer_id'          => $certificate->user_id,
            'created_at'         => $certificate->created_at->format('Y-m-d H:i:s'),
        ]);

        return '0x' . hash('sha256', $data);
    }

    /**
     * Store certificate hash on Polygon blockchain
     * Uses Node.js script for transaction signing (more reliable than PHP)
     */
    public function storeCertificateHash(Certificate $certificate): ?string
    {
        if (! $this->isEnabled()) {
            Log::warning('BlockchainService: Blockchain not enabled or not configured');
            return null;
        }

        try {
            // Generate certificate hash
            $certHash = $this->generateCertificateHash($certificate);

            // Try Node.js signing first (most reliable)
            $txHash = $this->signWithNode($certHash);

            // Fallback to direct RPC if Node.js not available
            if (! $txHash) {
                $txHash = $this->signWithDirectRpc($certHash);
            }

            if ($txHash) {
                // Update certificate with blockchain info
                $certificate->update([
                    'blockchain_hash'        => $certHash,
                    'blockchain_tx_hash'     => $txHash,
                    'blockchain_status'      => 'confirmed',
                    'blockchain_verified_at' => now(),
                ]);

                Log::info("Certificate {$certificate->certificate_number} stored on blockchain: {$txHash}");

                return $txHash;
            }

            // If all methods fail, still save the hash for reference
            $certificate->update([
                'blockchain_hash'   => $certHash,
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
    protected function signWithNode(string $dataHash): ?string
    {
        $scriptPath = base_path('scripts/sign_transaction.js');

        if (! file_exists($scriptPath)) {
            Log::warning('BlockchainService: Node script not found at ' . $scriptPath);
            return null;
        }

        try {
            // Build command with escaped arguments
            $nodeScript    = escapeshellarg($scriptPath);
            $privateKey    = escapeshellarg($this->privateKey);
            $walletAddress = escapeshellarg($this->walletAddress);
            $hash          = escapeshellarg($dataHash);
            $rpcUrl        = escapeshellarg($this->rpcUrl);

            $command = "node {$nodeScript} {$privateKey} {$walletAddress} {$hash} {$rpcUrl} 2>&1";

            Log::info('BlockchainService: Executing Node.js signing script');

            $output = shell_exec($command);
            $output = trim($output ?? '');

            Log::info('BlockchainService: Node.js output: ' . $output);

            if (str_starts_with($output, '0x') && strlen($output) === 66) {
                return $output;
            }

            // Check if output contains error
            if (str_contains($output, 'Error:')) {
                Log::error('BlockchainService Node.js Error: ' . $output);
            }

            return null;

        } catch (\Exception $e) {
            Log::error('BlockchainService: Node.js execution failed: ' . $e->getMessage());
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
            if (! $nonce) {
                return null;
            }

            // Get gas price
            $gasPrice = $this->getGasPrice();
            if (! $gasPrice) {
                $gasPrice = '0x3B9ACA00'; // 1 Gwei
            }

            // Build unsigned transaction
            $txParams = [
                'from'     => $this->walletAddress,
                'to'       => $this->walletAddress,
                'value'    => '0x0',
                'gas'      => '0x6270', // ~25200 gas for data
                'gasPrice' => $gasPrice,
                'nonce'    => $nonce,
                'data'     => $dataHash,
            ];

            // Try eth_sendTransaction (works if wallet is unlocked on node)
            $response = Http::timeout(30)->post($this->rpcUrl, [
                'jsonrpc' => '2.0',
                'method'  => 'eth_sendTransaction',
                'params'  => [$txParams],
                'id'      => 1,
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
            'method'  => 'eth_getTransactionCount',
            'params'  => [$this->walletAddress, 'latest'],
            'id'      => 1,
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
            'method'  => 'eth_gasPrice',
            'params'  => [],
            'id'      => 1,
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
            'method'  => 'eth_getTransactionReceipt',
            'params'  => [$txHash],
            'id'      => 1,
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
     * Verify if a certificate hash exists on blockchain
     */
    public function verifyCertificateOnChain(string $txHash): ?array
    {
        $response = Http::timeout(30)->post($this->rpcUrl, [
            'jsonrpc' => '2.0',
            'method'  => 'eth_getTransactionByHash',
            'params'  => [$txHash],
            'id'      => 1,
        ]);

        if ($response->successful() && isset($response['result'])) {
            $tx = $response['result'];
            if ($tx) {
                return [
                    'hash'        => $tx['hash'],
                    'from'        => $tx['from'],
                    'to'          => $tx['to'],
                    'data'        => $tx['input'],
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
                'method'  => 'eth_getBalance',
                'params'  => [$this->walletAddress, 'latest'],
                'id'      => 1,
            ]);

            if ($response->successful() && isset($response->json()['result'])) {
                $balanceWei   = hexdec($response->json()['result']);
                $balanceMatic = $balanceWei / 1e18;

                return [
                    'wei'       => $balanceWei,
                    'matic'     => round($balanceMatic, 6),
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
                'method'  => 'eth_getTransactionCount',
                'params'  => [$this->walletAddress, 'latest'],
                'id'      => 1,
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
        $avgGasCostMatic = 0.003; // ~0.003 MATIC per transaction
        $remainingCerts  = $balance ? floor($balance['matic'] / $avgGasCostMatic) : 0;

        return [
            'enabled'           => $this->isEnabled(),
            'wallet_address'    => $this->walletAddress,
            'short_address'     => $this->walletAddress ? substr($this->walletAddress, 0, 6) . '...' . substr($this->walletAddress, -4) : null,
            'balance'           => $balance,
            'transaction_count' => $txCount,
            'remaining_certs'   => $remainingCerts,
            'network'           => $this->chainId === '80002' ? 'Polygon Amoy (Testnet)' : 'Polygon Mainnet',
            'explorer_url'      => config('blockchain.explorer_url', 'https://amoy.polygonscan.com'),
            'rpc_url'           => $this->rpcUrl,
        ];
    }

    /**
     * Get wallet address
     */
    public function getWalletAddress(): string
    {
        return $this->walletAddress;
    }
}
