<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\BlockchainService;

echo "=== BLOCKCHAIN SERVICE TEST ===" . PHP_EOL . PHP_EOL;

$bs = new BlockchainService();

echo "Enabled: " . ($bs->isEnabled() ? "✅ Yes" : "❌ No") . PHP_EOL;

$balance = $bs->getWalletBalance();
echo "Wallet Balance: " . ($balance ?? "N/A") . " POL" . PHP_EOL;

$gasPrice = $bs->getGasPrice();
echo "Gas Price: " . ($gasPrice ? hexdec($gasPrice) / 1e9 . " Gwei" : "N/A") . PHP_EOL;

$nonce = $bs->getNonce();
echo "Current Nonce: " . ($nonce ? hexdec($nonce) : "N/A") . PHP_EOL;

echo PHP_EOL . "=== TEST COMPLETE ===" . PHP_EOL;
