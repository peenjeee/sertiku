<?php

use App\Services\BlockchainService;
use Illuminate\Support\Facades\Log;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- DEBUGGER START ---\n";
echo "1. Checking Config via helper:\n";
$configAddr = config('blockchain.contract_address');
echo "   config('blockchain.contract_address') = " . var_export($configAddr, true) . "\n";

echo "2. Checking Env via helper:\n";
$envAddr = env('POLYGON_CONTRACT_ADDRESS');
echo "   env('POLYGON_CONTRACT_ADDRESS') = " . var_export($envAddr, true) . "\n";

echo "3. Instantiating BlockchainService...\n";
try {
    $service = app(BlockchainService::class);
    // Use reflection to access protected property
    $reflection = new ReflectionClass($service);
    $property = $reflection->getProperty('contractAddress');
    $property->setAccessible(true);
    $serviceAddr = $property->getValue($service);

    echo "   BlockchainService->contractAddress = " . var_export($serviceAddr, true) . "\n";

    if ($serviceAddr === $configAddr) {
        echo "   [OK] Service matches Config.\n";
    } else {
        echo "   [FAIL] Service does NOT match Config!\n";
    }

} catch (\Exception $e) {
    echo "   [ERROR] Could not instantiate service: " . $e->getMessage() . "\n";
}

echo "--- DEBUGGER END ---\n";
