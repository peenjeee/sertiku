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
     * Uses a simple transaction with data field containing the hash
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

            // Get current nonce
            $nonce = $this->getNonce();
            if ($nonce === null) {
                throw new \Exception('Failed to get nonce');
            }

            // Get gas price
            $gasPrice = $this->getGasPrice();
            if ($gasPrice === null) {
                $gasPrice = '0x3B9ACA00'; // 1 Gwei default
            }

            // Build transaction
            $transaction = [
                'from'     => $this->walletAddress,
                'to'       => $this->walletAddress, // Send to self with data
                'value'    => '0x0',
                'gas'      => '0x5208', // 21000 gas
                'gasPrice' => $gasPrice,
                'nonce'    => $nonce,
                'data'     => $certHash,
                'chainId'  => hexdec($this->chainId),
            ];

            // Sign and send transaction
            $txHash = $this->signAndSendTransaction($transaction);

            if ($txHash) {
                // Update certificate with blockchain info
                $certificate->update([
                    'blockchain_hash'        => $certHash,
                    'blockchain_tx_hash'     => $txHash,
                    'blockchain_status'      => 'pending',
                    'blockchain_verified_at' => now(),
                ]);

                Log::info("Certificate {$certificate->certificate_number} stored on blockchain: {$txHash}");
            }

            return $txHash;

        } catch (\Exception $e) {
            Log::error('BlockchainService Error: ' . $e->getMessage());

            $certificate->update([
                'blockchain_status' => 'failed',
            ]);

            return null;
        }
    }

    /**
     * Get current nonce for wallet
     */
    protected function getNonce(): ?string
    {
        $response = Http::post($this->rpcUrl, [
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
    protected function getGasPrice(): ?string
    {
        $response = Http::post($this->rpcUrl, [
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
     * Sign transaction using private key and send to network
     * Note: For production, use a proper library like web3.php or kornrunner/ethereum-offline-raw-tx
     */
    protected function signAndSendTransaction(array $transaction): ?string
    {
        // For a complete implementation, you'd need to:
        // 1. RLP encode the transaction
        // 2. Sign with private key using secp256k1
        // 3. Send raw transaction

        // Using a simplified approach with eth_sendTransaction for testing
        // In production, use a proper signing library

        try {
            // For testnet, we'll use a workaround by calling our own signing endpoint
            // or use the web3.php package

            // Simplified: Store the hash as data and create a minimal transaction
            $response = Http::timeout(30)->post($this->rpcUrl, [
                'jsonrpc' => '2.0',
                'method'  => 'eth_sendRawTransaction',
                'params'  => [$this->createSignedTransaction($transaction)],
                'id'      => 1,
            ]);

            if ($response->successful() && isset($response['result'])) {
                return $response['result'];
            }

            // If signing fails, log and return null
            Log::warning('BlockchainService: Transaction signing/sending failed', [
                'response' => $response->json(),
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('BlockchainService signAndSendTransaction: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a signed raw transaction
     * This is a placeholder - for production use kornrunner/ethereum-offline-raw-tx
     */
    protected function createSignedTransaction(array $transaction): string
    {
        // For a full implementation, install: composer require kornrunner/ethereum-offline-raw-tx
        // Then use:
        // $tx = new \kornrunner\Ethereum\Transaction($nonce, $gasPrice, $gasLimit, $to, $value, $data);
        // $signedTx = $tx->getRaw($privateKey, $chainId);

        // For now, return empty - user needs to install the signing package
        // This is logged as a warning so they know to complete the setup
        Log::warning('BlockchainService: Transaction signing not fully implemented. Install kornrunner/ethereum-offline-raw-tx for full functionality.');

        return '';
    }

    /**
     * Get wallet balance in MATIC
     */
    public function getWalletBalance(): ?string
    {
        if (! $this->isEnabled()) {
            return null;
        }

        $response = Http::post($this->rpcUrl, [
            'jsonrpc' => '2.0',
            'method'  => 'eth_getBalance',
            'params'  => [$this->walletAddress, 'latest'],
            'id'      => 1,
        ]);

        if ($response->successful() && isset($response['result'])) {
            $balanceWei   = hexdec($response['result']);
            $balanceMatic = $balanceWei / 1e18;
            return number_format($balanceMatic, 4);
        }

        return null;
    }

    /**
     * Check transaction status
     */
    public function getTransactionStatus(string $txHash): ?string
    {
        $response = Http::post($this->rpcUrl, [
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
}
