<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected string $apiUrl = 'https://api.fonnte.com/send';
    protected ?string $token;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
    }

    /**
     * Check if Fonnte is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->token);
    }

    /**
     * Send WhatsApp message
     * 
     * @param string $phone Phone number (with or without country code)
     * @param string $message Message to send
     * @return array
     */
    public function send(string $phone, string $message): array
    {
        if (!$this->isConfigured()) {
            Log::warning('Fonnte: Token not configured');
            return [
                'success' => false,
                'message' => 'Fonnte token not configured',
            ];
        }

        // Format phone number (ensure it starts with country code)
        $phone = $this->formatPhone($phone);

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->apiUrl, [
                        'target' => $phone,
                        'message' => $message,
                        'countryCode' => '62', // Indonesia
                    ]);

            $result = $response->json();

            if ($response->successful() && isset($result['status']) && $result['status'] === true) {
                Log::info('Fonnte: Message sent successfully to ' . $phone);
                return [
                    'success' => true,
                    'message' => 'Message sent successfully',
                    'data' => $result,
                ];
            }

            Log::error('Fonnte: Failed to send message', [
                'phone' => $phone,
                'response' => $result,
            ]);

            return [
                'success' => false,
                'message' => $result['reason'] ?? 'Failed to send message',
                'data' => $result,
            ];
        } catch (\Exception $e) {
            Log::error('Fonnte: Exception while sending message', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Format phone number to international format
     */
    protected function formatPhone(string $phone): string
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If starts with 0, replace with 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // If doesn't start with country code, add 62
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    /**
     * Send invoice message
     */
    public function sendInvoice(
        string $phone,
        string $customerName,
        string $orderNumber,
        string $packageName,
        int $amount,
        string $paymentDate
    ): array {
        $formattedAmount = 'Rp ' . number_format($amount, 0, ',', '.');

        $message = "🧾 *INVOICE PEMBAYARAN SERTIKU*\n\n";
        $message .= "Halo {$customerName}! 👋\n\n";
        $message .= "Terima kasih telah berlangganan SertiKu.\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━\n";
        $message .= "📋 *Detail Transaksi*\n";
        $message .= "━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "📦 Paket: *{$packageName}*\n";
        $message .= "🔢 No. Order: {$orderNumber}\n";
        $message .= "💰 Total: *{$formattedAmount}*\n";
        $message .= "📅 Tanggal: {$paymentDate}\n";
        $message .= "✅ Status: *LUNAS*\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "Paket Anda sudah aktif dan siap digunakan! 🚀\n\n";
        $message .= "🔗 Akses dashboard:\n";
        $message .= config('app.url') . "/lembaga\n\n";
        $message .= "Jika ada pertanyaan, silakan hubungi kami.\n\n";
        $message .= "Salam hangat,\n";
        $message .= "*Tim SertiKu* 💙";

        return $this->send($phone, $message);
    }
}
