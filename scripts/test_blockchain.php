<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Certificate;
use App\Services\BlockchainService;

echo "=== TEST BLOCKCHAIN TRANSACTION ===" . PHP_EOL . PHP_EOL;

$bs = new BlockchainService();

// Check if enabled
echo "1. Service Enabled: " . ($bs->isEnabled() ? "✅ Yes" : "❌ No") . PHP_EOL;

// Get wallet balance
$balance = $bs->getWalletBalance();
echo "2. Wallet Balance: " . ($balance ?? "N/A") . " POL" . PHP_EOL;

// Find latest certificate with blockchain_enabled = true
$cert = Certificate::where('blockchain_enabled', true)
    ->whereNull('blockchain_tx_hash')
    ->orderBy('created_at', 'desc')
    ->first();

if ($cert) {
    echo PHP_EOL . "3. Found pending certificate: " . $cert->certificate_number . PHP_EOL;
    echo "   Recipient: " . $cert->recipient_name . PHP_EOL;
    echo "   Status: " . ($cert->blockchain_status ?? 'none') . PHP_EOL;

    echo PHP_EOL . "4. Attempting blockchain upload..." . PHP_EOL;

    $txHash = $bs->storeCertificateHash($cert);

    if ($txHash) {
        echo "   ✅ SUCCESS! TX Hash: " . $txHash . PHP_EOL;
        echo "   View on PolygonScan: https://amoy.polygonscan.com/tx/" . $txHash . PHP_EOL;
    } else {
        echo "   ❌ FAILED - Check Laravel logs for details" . PHP_EOL;
        $cert->refresh();
        echo "   Status updated to: " . ($cert->blockchain_status ?? 'unknown') . PHP_EOL;
    }
} else {
    echo PHP_EOL . "3. No pending certificates found with blockchain_enabled = true" . PHP_EOL;
    echo "   Create a new certificate with blockchain toggle ON to test." . PHP_EOL;
}

echo PHP_EOL . "=== TEST COMPLETE ===" . PHP_EOL;
