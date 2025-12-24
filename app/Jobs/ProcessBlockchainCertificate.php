<?php
namespace App\Jobs;

use App\Models\ActivityLog;
use App\Models\Certificate;
use App\Services\BlockchainService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessBlockchainCertificate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying.
     */
    public int $backoff = 30;

    /**
     * The certificate to process.
     */
    protected Certificate $certificate;

    /**
     * Create a new job instance.
     */
    public function __construct(Certificate $certificate)
    {
        $this->certificate = $certificate;
    }

    /**
     * Execute the job.
     */
    public function handle(BlockchainService $blockchainService): void
    {
        Log::info("Processing blockchain for certificate: {$this->certificate->certificate_number}");

        try {
            // Mark as processing
            $this->certificate->update([
                'blockchain_status' => 'processing',
            ]);

            // Store on blockchain using smart contract
            $txHash = $blockchainService->storeWithContract($this->certificate);

            if ($txHash) {
                Log::info("Certificate {$this->certificate->certificate_number} stored on blockchain: {$txHash}");

                // Log activity for blockchain transaction
                ActivityLog::log(
                    'blockchain_tx',
                    "Sertifikat {$this->certificate->certificate_number} berhasil disimpan di blockchain",
                    $this->certificate,
                    ['tx_hash' => $txHash]
                );

                // Dispatch IPFS job after blockchain confirms (so metadata includes tx_hash)
                $ipfsService = new \App\Services\IpfsService();
                Log::info("Blockchain job: Checking IPFS enabled: " . ($ipfsService->isEnabled() ? 'yes' : 'no'));
                if ($ipfsService->isEnabled()) {
                    // Refresh certificate to get updated blockchain data
                    $this->certificate->refresh();
                    Log::info("Blockchain job: Dispatching ProcessIpfsCertificate for {$this->certificate->certificate_number}");
                    \App\Jobs\ProcessIpfsCertificate::dispatch($this->certificate);
                }
            } else {
                Log::warning("Failed to store certificate {$this->certificate->certificate_number} on blockchain");
            }

        } catch (\Exception $e) {
            Log::error("Blockchain job error for certificate {$this->certificate->certificate_number}: " . $e->getMessage());

            $this->certificate->update([
                'blockchain_status' => 'failed',
            ]);

            throw $e; // Re-throw to trigger retry
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Blockchain job permanently failed for certificate {$this->certificate->certificate_number}: " . $exception->getMessage());

        $this->certificate->update([
            'blockchain_status' => 'failed',
        ]);
    }
}
