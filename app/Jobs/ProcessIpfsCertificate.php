<?php
namespace App\Jobs;

use App\Models\Certificate;
use App\Services\IpfsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessIpfsCertificate implements ShouldQueue
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
     * Create a new job instance.
     */
    public function __construct(
        public Certificate $certificate
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(IpfsService $ipfsService): void
    {
        Log::info("ProcessIpfsCertificate: Starting IPFS upload for certificate {$this->certificate->certificate_number}");

        try {
            // Upload certificate metadata to IPFS
            $result = $ipfsService->uploadCertificateMetadata($this->certificate);

            if ($result && $result['success']) {
                $this->certificate->update([
                    'ipfs_cid' => $result['cid'],
                    'ipfs_url' => $result['url'],
                    'ipfs_uploaded_at' => now(),
                ]);

                // Log activity for IPFS upload
                \App\Models\ActivityLog::log(
                    'ipfs_upload',
                    "Sertifikat {$this->certificate->certificate_number} berhasil diupload ke IPFS",
                    $this->certificate,
                    ['cid' => $result['cid'], 'url' => $result['url']]
                );

                Log::info("ProcessIpfsCertificate: Successfully uploaded to IPFS. CID: {$result['cid']}");
            } else {
                Log::error("ProcessIpfsCertificate: Failed to upload certificate {$this->certificate->certificate_number} to IPFS");
            }

        } catch (\Exception $e) {
            Log::error("ProcessIpfsCertificate: Error - " . $e->getMessage());
            throw $e; // Rethrow to trigger retry
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("ProcessIpfsCertificate: Job failed for certificate {$this->certificate->certificate_number}. Error: " . $exception->getMessage());
    }
}
