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
     * Whether IPFS upload was requested by user.
     */
    protected bool $ipfsEnabled;

    /**
     * Create a new job instance.
     */
    public function __construct(Certificate $certificate, bool $ipfsEnabled = false)
    {
        $this->certificate = $certificate;
        $this->ipfsEnabled = $ipfsEnabled;
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

                // NOTE: IPFS is now dispatched SEPARATELY from controller to avoid nested sync dispatch issues
                // Previously this caused infinite loading when blockchain + IPFS were both enabled in sync mode
                // The controller now dispatches ProcessIpfsCertificate after ProcessBlockchainCertificate completes
            } else {
                Log::warning("Failed to store certificate {$this->certificate->certificate_number} on blockchain");
            }

        } catch (\Exception $e) {
            Log::error("Blockchain job error for certificate {$this->certificate->certificate_number}: " . $e->getMessage());

            $this->certificate->update([
                'blockchain_status' => 'failed',
            ]);

            // DO NOT re-throw in sync mode - it crashes the entire request
            // In async mode (database/redis queue), the failed() method handles this
            // throw $e;
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
