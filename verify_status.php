<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Certificate Status Summary ===\n";
echo "Pending: " . \App\Models\Certificate::where('blockchain_status', 'pending')->count() . "\n";
echo "Processing: " . \App\Models\Certificate::where('blockchain_status', 'processing')->count() . "\n";
echo "Confirmed: " . \App\Models\Certificate::where('blockchain_status', 'confirmed')->count() . "\n";
echo "Failed: " . \App\Models\Certificate::where('blockchain_status', 'failed')->count() . "\n";

// Check specific certificate
$cert = \App\Models\Certificate::where('certificate_number', 'SERT-202512-GBZNOB')->first();
if ($cert) {
    echo "\n=== SERT-202512-GBZNOB ===\n";
    echo "Status: " . $cert->blockchain_status . "\n";
    echo "TX Hash: " . ($cert->blockchain_tx_hash ?? 'None') . "\n";
}
