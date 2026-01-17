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
            // Try Smart Contract approach first (full data on-chain like Node.js)
            if (!empty($this->contractAddress)) {
                $txHash = $this->signWithContract($certificate);
                if ($txHash) {
                    Log::info("Certificate {$certificate->certificate_number} stored on blockchain via contract: {$txHash}");
                    return $txHash;
                }
                Log::warning('BlockchainService: Contract call failed, falling back to simple hash');
            }

            // Fallback: Simple hash-only transaction (if contract not configured)
            $certHash = $this->generateCertificateHash($certificate);
            $txHash = $this->signWithPhp($certHash);

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
                'blockchain_hash' => $certHash ?? $this->generateCertificateHash($certificate),
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
            Log::info('BlockchainService signWithPhp: Starting with hash ' . $dataHash);

            // Get nonce
            $nonce = $this->getNonce();
            if (!$nonce) {
                Log::error('BlockchainService signWithPhp: Failed to get nonce');
                return null;
            }
            Log::info('BlockchainService signWithPhp: Nonce = ' . $nonce);

            // Get gas price
            $gasPrice = $this->getGasPrice();
            if (!$gasPrice) {
                $gasPrice = '0x3B9ACA00'; // 1 Gwei fallback
                Log::info('BlockchainService signWithPhp: Using fallback gas price');
            }
            Log::info('BlockchainService signWithPhp: GasPrice = ' . $gasPrice);

            // Prepare transaction data
            Log::info('BlockchainService signWithPhp: Creating transaction to ' . $this->walletAddress);
            $transaction = new \kornrunner\Ethereum\Transaction(
                $nonce,
                $gasPrice,
                '0x6270', // Gas limit ~25200
                $this->walletAddress, // To (self)
                '0x0', // Value
                $dataHash // Data
            );

            // Sign transaction
            $privateKey = $this->privateKey;
            Log::info('BlockchainService signWithPhp: Signing with chainId ' . $this->chainId);
            $rawTx = $transaction->getRaw($privateKey, $this->chainId);
            Log::info('BlockchainService signWithPhp: Raw transaction created, length = ' . strlen($rawTx));

            // Broadcast transaction
            Log::info('BlockchainService signWithPhp: Broadcasting to ' . $this->rpcUrl);
            $response = Http::timeout(0)->post($this->rpcUrl, [
                'jsonrpc' => '2.0',
                'method' => 'eth_sendRawTransaction',
                'params' => ['0x' . $rawTx],
                'id' => 1,
            ]);

            Log::info('BlockchainService signWithPhp: Response status = ' . $response->status());

            if ($response->successful()) {
                $result = $response->json();
                Log::info('BlockchainService signWithPhp: Response = ' . json_encode($result));

                if (isset($result['result'])) {
                    Log::info('BlockchainService signWithPhp: SUCCESS tx = ' . $result['result']);
                    return $result['result']; // Returns tx hash
                }

                if (isset($result['error'])) {
                    Log::error('BlockchainService PHP Sign Error: ' . json_encode($result['error']));
                }
            } else {
                Log::error('BlockchainService signWithPhp: HTTP failed, body = ' . $response->body());
            }

            return null;

        } catch (\Exception $e) {
            Log::error('BlockchainService PHP Sign Exception: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return null;
        }
    }

    /**
     * Sign transaction calling Smart Contract storeCertificate function
     * This encodes the full certificate data like Node.js approach
     */
    protected function signWithContract(Certificate $certificate): ?string
    {
        try {
            if (empty($this->contractAddress)) {
                Log::error('BlockchainService signWithContract: Contract address not configured');
                return null;
            }

            // Generate certificate hash
            $certHash = $this->generateCertificateHash($certificate);
            Log::info('BlockchainService signWithContract: Starting with hash ' . $certHash);

            // Prepare certificate data
            $certNumber = $certificate->certificate_number ?? '';
            $recipientName = $certificate->recipient_name ?? '';
            $courseName = $certificate->course_name ?? '';
            $issueDate = $certificate->issue_date ? $certificate->issue_date->format('Y-m-d') : '';
            $issuerBaseName = $certificate->user ? ($certificate->user->institution_name ?? $certificate->user->name ?? '') : '';

            // Build issuer name with file hashes in JSON format for structured on-chain storage
            $fileHashes = $certificate->getFileHashes();

            // Construct the comprehensive JSON payload matching the requested schema
            $jsonPayload = [
                'certificate_number' => $certNumber,
                'recipient_name' => $recipientName,
                'course_name' => $courseName,
                'issue_date' => $issueDate,
                'issuer' => $issuerBaseName,
                // File hashes will be mapped to the expected keys below
            ];

            // Map PDF hashes
            if (!empty($fileHashes['certificate'])) {
                $jsonPayload['pdf_file_hashes'] = $fileHashes['certificate'];
            }

            // Map QR hashes
            if (!empty($fileHashes['qr_code'])) {
                $jsonPayload['qr_code_hashes'] = $fileHashes['qr_code'];
            }

            // Map Template hashes (from certificate's template relationship)
            if ($certificate->template) {
                $tplHashes = [];
                if (!empty($certificate->template->sha256))
                    $tplHashes['sha256'] = $certificate->template->sha256;
                if (!empty($certificate->template->md5))
                    $tplHashes['md5'] = $certificate->template->md5;

                if (!empty($tplHashes)) {
                    $jsonPayload['template_hashes'] = $tplHashes;
                }
            }

            // Encode as pretty-printed JSON for better readability on blockchain explorer
            $certificateData = json_encode($jsonPayload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

            // Encode the smart contract function call (HYBRID CONTRACT - 2 params only)
            // Function: storeCertificate(bytes32 _dataHash, string _certificateData)
            // Add newline prefix to separate JSON from hash in block explorer view
            $functionData = $this->encodeStoreCertificateHybrid($certHash, "\n" . $certificateData);

            Log::info('BlockchainService signWithContract: Encoded function data length = ' . strlen($functionData));

            // Get nonce
            $nonce = $this->getNonce();
            if (!$nonce) {
                Log::error('BlockchainService signWithContract: Failed to get nonce');
                return null;
            }
            Log::info('BlockchainService signWithContract: Nonce = ' . $nonce);

            // Get gas price
            $gasPrice = $this->getGasPrice();
            if (!$gasPrice) {
                $gasPrice = '0x3B9ACA00'; // 1 Gwei fallback
            }
            Log::info('BlockchainService signWithContract: GasPrice = ' . $gasPrice);

            // Create transaction to CONTRACT address (not wallet)
            Log::info('BlockchainService signWithContract: Creating transaction to contract ' . $this->contractAddress);
            $transaction = new \kornrunner\Ethereum\Transaction(
                $nonce,
                $gasPrice,
                '0x30D40', // Gas limit ~200000 for hybrid contract (reduced from 1M)
                $this->contractAddress, // To: Smart Contract
                '0x0', // Value: 0
                $functionData // Data: encoded function call
            );

            // Sign transaction
            $rawTx = $transaction->getRaw($this->privateKey, $this->chainId);
            Log::info('BlockchainService signWithContract: Raw transaction created, length = ' . strlen($rawTx));

            // Broadcast transaction
            $response = Http::timeout(0)->post($this->rpcUrl, [
                'jsonrpc' => '2.0',
                'method' => 'eth_sendRawTransaction',
                'params' => ['0x' . $rawTx],
                'id' => 1,
            ]);

            Log::info('BlockchainService signWithContract: Response status = ' . $response->status());

            if ($response->successful()) {
                $result = $response->json();
                Log::info('BlockchainService signWithContract: Response = ' . json_encode($result));

                if (isset($result['result'])) {
                    $txHash = $result['result'];
                    Log::info('BlockchainService signWithContract: SUCCESS tx = ' . $txHash);

                    // Update certificate with blockchain info
                    $certificate->update([
                        'blockchain_hash' => $certHash,
                        'blockchain_tx_hash' => $txHash,
                        'blockchain_status' => 'confirmed',
                        'blockchain_verified_at' => now(),
                    ]);

                    return $txHash;
                }

                if (isset($result['error'])) {
                    Log::error('BlockchainService signWithContract Error: ' . json_encode($result['error']));
                }
            } else {
                Log::error('BlockchainService signWithContract: HTTP failed, body = ' . $response->body());
            }

            return null;

        } catch (\Exception $e) {
            Log::error('BlockchainService signWithContract Exception: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return null;
        }
    }

    /**
     * ABI-encode the storeCertificate function call for HYBRID contract
     * Function signature: storeCertificate(bytes32,string)
     * Gas optimized: ~150,000 gas (vs ~721,000 with old contract)
     */
    protected function encodeStoreCertificateHybrid(
        string $dataHash,
        string $certificateData
    ): string {
        // Function selector: keccak256("storeCertificate(bytes32,string)")[:4]
        $functionSelector = $this->getFunctionSelector('storeCertificate(bytes32,string)');

        // Remove 0x prefix from hash and pad to 32 bytes
        $hashBytes = str_pad(substr($dataHash, 2), 64, '0', STR_PAD_LEFT);

        $staticPart = '';
        $dynamicPart = '';

        // Param 0: bytes32 dataHash (static, inline)
        $staticPart .= $hashBytes;

        // Param 1: string certificateData (dynamic)
        // Offset = 2 * 32 = 64 bytes = 0x40 (2 params: bytes32 + string offset)
        $staticPart .= str_pad(dechex(64), 64, '0', STR_PAD_LEFT);

        // Encode the string
        $strHex = bin2hex($certificateData);
        $strLen = strlen($certificateData);
        $paddedLen = ceil($strLen / 32) * 32;
        if ($paddedLen == 0)
            $paddedLen = 32;

        // Length (32 bytes)
        $dynamicPart .= str_pad(dechex($strLen), 64, '0', STR_PAD_LEFT);
        // String data (padded to 32 bytes)
        $dynamicPart .= str_pad($strHex, $paddedLen * 2, '0', STR_PAD_RIGHT);

        return '0x' . $functionSelector . $staticPart . $dynamicPart;
    }

    /**
     * Legacy ABI-encode for old contract (kept for backward compatibility)
     * Function signature: storeCertificate(bytes32,string,string,string,string,string)
     * @deprecated Use encodeStoreCertificateHybrid instead
     */
    protected function encodeStoreCertificate(
        string $dataHash,
        string $certNumber,
        string $recipientName,
        string $courseName,
        string $issueDate,
        string $issuerName
    ): string {
        $functionSelector = $this->getFunctionSelector('storeCertificate(bytes32,string,string,string,string,string)');
        $hashBytes = str_pad(substr($dataHash, 2), 64, '0', STR_PAD_LEFT);

        $staticPart = '';
        $dynamicPart = '';
        $staticPart .= $hashBytes;

        $baseOffset = 6 * 32;
        $strings = [$certNumber, $recipientName, $courseName, $issueDate, $issuerName];
        $currentOffset = $baseOffset;
        $offsets = [];

        foreach ($strings as $str) {
            $offsets[] = $currentOffset;
            $strLen = strlen($str);
            $paddedLen = ceil($strLen / 32) * 32;
            if ($paddedLen == 0)
                $paddedLen = 32;
            $currentOffset += 32 + $paddedLen;
        }

        foreach ($offsets as $offset) {
            $staticPart .= str_pad(dechex($offset), 64, '0', STR_PAD_LEFT);
        }

        foreach ($strings as $str) {
            $strHex = bin2hex($str);
            $strLen = strlen($str);
            $paddedLen = ceil($strLen / 32) * 32;
            if ($paddedLen == 0)
                $paddedLen = 32;
            $dynamicPart .= str_pad(dechex($strLen), 64, '0', STR_PAD_LEFT);
            $dynamicPart .= str_pad($strHex, $paddedLen * 2, '0', STR_PAD_RIGHT);
        }

        return '0x' . $functionSelector . $staticPart . $dynamicPart;
    }

    /**
     * Get function selector (first 4 bytes of keccak256 hash)
     */
    protected function getFunctionSelector(string $functionSignature): string
    {
        // Use kornrunner/keccak for hashing
        $hash = \kornrunner\Keccak::hash($functionSignature, 256);
        return substr($hash, 0, 8); // First 4 bytes = 8 hex chars
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
            $response = Http::timeout(0)->post($this->rpcUrl, [
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
        $response = Http::timeout(0)->post($this->rpcUrl, [
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
        $response = Http::timeout(0)->post($this->rpcUrl, [
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
        $response = Http::timeout(0)->post($this->rpcUrl, [
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
        $response = Http::timeout(0)->post($this->rpcUrl, [
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
            $response = Http::timeout(0)->post($this->rpcUrl, [
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
     * Check if wallet balance is low (<= minBalance)
     * Returns true if low balance OR if balance cannot be determined (null)
     */
    public function isLowBalance(float $minBalance = 0.01): bool
    {
        $balance = $this->getWalletBalance();

        \Illuminate\Support\Facades\Log::info('Debug isLowBalance:', [
            'balance_raw' => $balance,
            'min_balance' => $minBalance,
            'is_null' => $balance === null,
            'matic_val' => $balance['matic'] ?? 'N/A',
            'result' => ($balance === null || !isset($balance['matic'])) ? true : ($balance['matic'] <= $minBalance)
        ]);

        // If API fails or returns null, consider it "low/unsafe" to proceed
        if ($balance === null || !isset($balance['matic'])) {
            return true;
        }

        return $balance['matic'] <= $minBalance;
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
            $response = Http::timeout(0)->post($this->rpcUrl, [
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
        $avgGasCostMatic = 0.003; // ~0.003 MATIC per certificate (user confirmed)
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
            'smart_contract_address' => $this->contractAddress,
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

            // Add --max-old-space-size=150 (Increased from 50MB as per request)
            $command = "{$nodePath} --max-old-space-size=150 --no-warnings {$scriptPath} store {$hash} {$certNumber} {$recipientName} {$courseName} {$issueDate} {$issuerName} 2>&1";

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
            $command = "{$nodePath} --max-old-space-size=150 --no-warnings {$scriptPath} verify {$hash} 2>&1";

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
            $command = "{$nodePath} --max-old-space-size=150 --no-warnings {$scriptPath} stats 2>&1";
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
