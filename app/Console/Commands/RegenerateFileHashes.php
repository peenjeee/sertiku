<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use Illuminate\Console\Command;

class RegenerateFileHashes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'certificates:regenerate-hashes 
                            {--id= : Regenerate hash for specific certificate ID}
                            {--force : Force regenerate even if hash already exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate SHA256 and MD5 file hashes for certificates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->option('id');
        $force = $this->option('force');

        if ($id) {
            // Regenerate for specific certificate
            $certificate = Certificate::find($id);

            if (!$certificate) {
                $this->error("Certificate with ID {$id} not found.");
                return 1;
            }

            $result = $this->regenerateHash($certificate, $force);

            if ($result) {
                $this->info(" Certificate {$certificate->certificate_number}: Hash regenerated successfully.");
            } else {
                $this->warn(" Certificate {$certificate->certificate_number}: No files found to hash.");
            }

            return 0;
        }

        // Regenerate for all certificates
        $query = Certificate::query();

        if (!$force) {
            $query->whereNull('certificate_sha256');
        }

        $certificates = $query->get();
        $total = $certificates->count();

        if ($total === 0) {
            $this->info('No certificates need hash regeneration.');
            return 0;
        }

        $this->info("Found {$total} certificates to process...");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $success = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($certificates as $certificate) {
            $result = $this->regenerateHash($certificate, $force);

            if ($result === true) {
                $success++;
            } elseif ($result === false) {
                $skipped++;
            } else {
                $failed++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info(" Successfully regenerated: {$success}");
        $this->warn("⊘ Skipped (no files): {$skipped}");

        if ($failed > 0) {
            $this->error(" Failed: {$failed}");
        }

        return 0;
    }

    /**
     * Regenerate hash for a single certificate.
     */
    private function regenerateHash(Certificate $certificate, bool $force): ?bool
    {
        // Skip if already has hash and not forcing
        if (!$force && $certificate->certificate_sha256) {
            return null;
        }

        // Check if certificate has files
        $hasPdf = $certificate->pdf_path || $certificate->image_path;
        $hasQr = $certificate->qr_code_path;

        if (!$hasPdf && !$hasQr) {
            return false;
        }

        try {
            $hashes = $certificate->generateFileHashes();
            return !empty($hashes);
        } catch (\Exception $e) {
            $this->error("Error processing certificate {$certificate->id}: {$e->getMessage()}");
            return null;
        }
    }
}
