<?php

use App\Models\Order;
use Illuminate\Support\Facades\Http;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Simulating NOWPayments IPN...\n";

// 1. Get the latest pending valid order
$order = Order::where('status', 'pending')
    ->latest()
    ->first();

if (!$order) {
    // Fallback: finding latest pending regardless of method
    $order = Order::where('status', 'pending')->latest()->first();
}

if (!$order) {
    die("Error: No pending order found to simulate!\n");
}

echo "Found Pending Order: " . $order->order_number . "\n";
echo "Amount: " . $order->amount . "\n";

// 2. Prepare Payload
$payload = [
    'payment_id' => 'PREVAILED_TEST_' . time(),
    'payment_status' => 'finished', // This triggers 'paid' status
    'pay_address' => 'TEST_WALLET_ADDRESS',
    'price_amount' => (int) $order->amount,
    'price_currency' => 'idr',
    'pay_amount' => 0.001, // Dummy crypto amount
    'pay_currency' => 'btc',
    'order_id' => $order->order_number,
    'order_description' => 'Simulation Payment',
    'ipn_type' => 'invoice-transaction',
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s'),
];

// 3. Calculate Signature
$ipnSecret = config('services.nowpayments.ipn_secret');
if (empty($ipnSecret)) {
    die("Error: IPN Secret validation failed (config missing or empty)\n");
}

ksort($payload);
$stringToSign = json_encode($payload, JSON_UNESCAPED_SLASHES);
$signature = hash_hmac('sha512', $stringToSign, $ipnSecret);

echo "Payload: " . json_encode($payload) . "\n";
echo "Signature: " . $signature . "\n";

// 4. Send Request to Localhost
$url = 'http://127.0.0.1:8000/payment/nowpayments/callback';

try {
    $response = Http::withHeaders([
        'x-nowpayments-sig' => $signature,
        'Content-Type' => 'application/json'
    ])->post($url, $payload);

    echo "Response Status: " . $response->status() . "\n";
    echo "Response Body: " . $response->body() . "\n";

    if ($response->successful()) {
        echo "\n✅ SUCCESS! Order {$order->order_number} should be PAID now.\n";
        echo "Check 'Pesanan Saya' in dashboard.\n";
    } else {
        echo "\n❌ FAILED! Server returned error.\n";
    }

} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
