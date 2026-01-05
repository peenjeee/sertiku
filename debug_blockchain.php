<?php

/**
 * Debug script to test PHP blockchain signing
 * Run: php debug_blockchain.php
 */

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\BlockchainService;
use Illuminate\Support\Facades\Log;

echo "=== BLOCKCHAIN DEBUG ===\n\n";

$service = new BlockchainService();

echo "1. Blockchain Enabled: " . ($service->isEnabled() ? 'YES' : 'NO') . "\n";
echo "2. Wallet Address: " . $service->getWalletAddress() . "\n";
echo "3. Has Contract: " . ($service->hasContract() ? 'YES' : 'NO') . "\n";

echo "\n--- Testing RPC Connection ---\n";

try {
    $nonce = $service->getNonce();
    echo "4. Current Nonce: " . ($nonce ?? 'FAILED') . "\n";
} catch (\Exception $e) {
    echo "4. Nonce Error: " . $e->getMessage() . "\n";
}

try {
    $gasPrice = $service->getGasPrice();
    echo "5. Gas Price: " . ($gasPrice ?? 'FAILED') . "\n";
} catch (\Exception $e) {
    echo "5. Gas Price Error: " . $e->getMessage() . "\n";
}

try {
    $balance = $service->getWalletBalance();
    echo "6. Wallet Balance: " . ($balance ? $balance['formatted'] : 'FAILED') . "\n";
} catch (\Exception $e) {
    echo "6. Balance Error: " . $e->getMessage() . "\n";
}

echo "\n--- Testing PHP Signing (Dry Run) ---\n";

// Get a recent certificate to test with
$cert = \App\Models\Certificate::where('blockchain_status', 'pending')
    ->orWhere('blockchain_status', 'failed')
    ->first();

if ($cert) {
    echo "7. Test Certificate: " . $cert->certificate_number . "\n";
    echo "   Status: " . $cert->blockchain_status . "\n";

    echo "\n--- Attempting PHP Sign ---\n";
    try {
        $txHash = $service->storeCertificateHash($cert);
        if ($txHash) {
            echo "SUCCESS! TX Hash: " . $txHash . "\n";
        } else {
            echo "FAILED - No TX Hash returned\n";
            echo "Check storage/logs/laravel.log for details\n";
        }
    } catch (\Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    }
} else {
    echo "7. No pending/failed certificates to test\n";
}

echo "\n=== END DEBUG ===\n";
