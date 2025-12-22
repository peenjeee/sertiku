<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use Illuminate\Console\Command;

class RegenerateQrCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'certificates:regenerate-qr {--id= : Specific certificate ID to regenerate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate QR codes for all certificates with new URL format';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->option('id');

        if ($id) {
            $certificates = Certificate::where('id', $id)->get();
        } else {
            $certificates = Certificate::all();
        }

        $count = $certificates->count();
        $this->info("Regenerating QR codes for {$count} certificates...");

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        foreach ($certificates as $certificate) {
            try {
                $certificate->generateQrCode();
                $bar->advance();
            } catch (\Exception $e) {
                $this->error("\nError for {$certificate->certificate_number}: " . $e->getMessage());
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info("Done! {$count} QR codes regenerated.");

        return Command::SUCCESS;
    }
}
