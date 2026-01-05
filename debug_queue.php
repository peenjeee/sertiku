<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- DEBUG INFO ---\n";
echo "Queue Connection (config): " . Config::get('queue.default') . "\n";
echo "Blockchain Enabled: " . (Config::get('blockchain.enabled') ? 'YES' : 'NO') . "\n";
echo "Wallet Address: " . Config::get('blockchain.wallet_address') . "\n";
echo "------------------\n";

try {
    $jobsCount = DB::table('jobs')->count();
    echo "Pending Jobs in DB: " . $jobsCount . "\n";
} catch (\Exception $e) {
    echo "Error checking jobs table: " . $e->getMessage() . "\n";
}

try {
    $failedJobsCount = DB::table('failed_jobs')->count();
    echo "Failed Jobs in DB: " . $failedJobsCount . "\n";

    if ($failedJobsCount > 0) {
        $lastFailed = DB::table('failed_jobs')->latest()->first();
        echo "\nLast Failed Job Error:\n";
        echo substr($lastFailed->exception, 0, 500) . "...\n";
    }
} catch (\Exception $e) {
    echo "Error checking failed_jobs table: " . $e->getMessage() . "\n";
}

echo "------------------\n";
