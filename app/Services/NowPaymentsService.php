<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NowPaymentsService
{
    protected string $apiKey;
    protected string $apiUrl = 'https://api.nowpayments.io/v1';
    protected bool $sandbox = false;

    public function __construct()
    {
        $this->apiKey = config('services.nowpayments.api_key');
        $this->sandbox = config('services.nowpayments.sandbox', false);

        if ($this->sandbox) {
            $this->apiUrl = 'https://api-sandbox.nowpayments.io/v1';
        }
    }

    /**
     * Check if NOWPayments is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Get API status
     */
    public function getStatus(): array
    {
        try {
            $response = Http::get($this->apiUrl . '/status');
            return $response->json();
        } catch (\Exception $e) {
            Log::error('NOWPayments Status Error: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Get available currencies
     */
    public function getCurrencies(): array
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
            ])->get($this->apiUrl . '/currencies');

            return $response->json()['currencies'] ?? [];
        } catch (\Exception $e) {
            Log::error('NOWPayments Currencies Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get minimum payment amount for a currency
     */
    public function getMinimumAmount(string $currencyFrom, string $currencyTo = 'idr'): ?float
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
            ])->get($this->apiUrl . '/min-amount', [
                        'currency_from' => $currencyFrom,
                        'currency_to' => $currencyTo,
                    ]);

            return $response->json()['min_amount'] ?? null;
        } catch (\Exception $e) {
            Log::error('NOWPayments MinAmount Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get estimated price
     */
    public function getEstimate(float $amount, string $currencyFrom, string $currencyTo = 'idr'): ?array
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
            ])->get($this->apiUrl . '/estimate', [
                        'amount' => $amount,
                        'currency_from' => $currencyFrom,
                        'currency_to' => $currencyTo,
                    ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('NOWPayments Estimate Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a payment
     */
    public function createPayment(array $data): ?array
    {
        try {
            $payload = [
                'price_amount' => $data['amount'],
                'price_currency' => $data['currency'] ?? 'idr',
                'pay_currency' => $data['pay_currency'] ?? 'btc',
                'order_id' => $data['order_id'],
                'order_description' => $data['description'] ?? 'SertiKu Package Purchase',
                'ipn_callback_url' => $data['callback_url'] ?? route('payment.nowpayments.callback'),
            ];

            // Optional: success/cancel URLs for hosted checkout
            if (isset($data['success_url'])) {
                $payload['success_url'] = $data['success_url'];
            }
            if (isset($data['cancel_url'])) {
                $payload['cancel_url'] = $data['cancel_url'];
            }

            Log::info('NOWPayments CreatePayment Request', $payload);

            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/payment', $payload);

            $result = $response->json();
            Log::info('NOWPayments CreatePayment Response', $result);

            if (isset($result['payment_id'])) {
                return $result;
            }

            Log::error('NOWPayments CreatePayment Error', $result);
            return null;

        } catch (\Exception $e) {
            Log::error('NOWPayments CreatePayment Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create invoice (hosted checkout page)
     */
    public function createInvoice(array $data): ?array
    {
        try {
            $payload = [
                'price_amount' => $data['amount'],
                'price_currency' => $data['currency'] ?? 'idr',
                'order_id' => $data['order_id'],
                'order_description' => $data['description'] ?? 'SertiKu Package Purchase',
                'ipn_callback_url' => $data['callback_url'] ?? route('payment.nowpayments.callback'),
                'success_url' => $data['success_url'] ?? route('payment.success', $data['order_id']),
                'cancel_url' => $data['cancel_url'] ?? route('checkout', 'professional'),
            ];

            Log::info('NOWPayments CreateInvoice Request', $payload);

            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/invoice', $payload);

            $result = $response->json();
            Log::info('NOWPayments CreateInvoice Response', $result);

            if (isset($result['id'])) {
                return $result;
            }

            Log::error('NOWPayments CreateInvoice Error', $result);
            return null;

        } catch (\Exception $e) {
            Log::error('NOWPayments CreateInvoice Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus(string $paymentId): ?array
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
            ])->get($this->apiUrl . '/payment/' . $paymentId);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('NOWPayments GetPayment Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Verify IPN callback signature
     */
    public function verifyIpnSignature(array $data, string $signature): bool
    {
        $ipnSecret = config('services.nowpayments.ipn_secret');

        if (empty($ipnSecret)) {
            Log::warning('NOWPayments IPN Secret not configured');
            return false;
        }

        // Sort the data
        ksort($data);

        // Create the string to sign
        $stringToSign = json_encode($data, JSON_UNESCAPED_SLASHES);

        // Calculate signature
        $calculatedSignature = hash_hmac('sha512', $stringToSign, $ipnSecret);

        return hash_equals($calculatedSignature, $signature);
    }

    /**
     * Check if payment is completed
     */
    public function isPaymentCompleted(string $status): bool
    {
        return in_array($status, ['finished', 'confirmed', 'sending', 'partially_paid']);
    }
}
