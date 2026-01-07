<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
     * @param string|null $fileData URL or local file path
     * @param string|null $filename Filename for attachment
     * @return array
     */
    public function send(string $phone, string $message, ?string $fileData = null, ?string $filename = null): array
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
            $curl = curl_init();

            $postFields = [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62', // Indonesia
            ];

            // Handle file attachment via URL (Standard Fonnte Method for Production)
            // Note: On Localhost, this URL won't be reachable by Fonnte, so attachment might fail.
            // But the Link in message body will work.
            if ($fileData) {
                // We prioritize sending as URL
                $postFields['url'] = $fileData;
                if ($filename) {
                    $postFields['filename'] = $filename;
                }
            }

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->apiUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $postFields,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: ' . $this->token
                ),
            ));

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $error = curl_error($curl);

            curl_close($curl);

            if ($error) {
                Log::error('Fonnte: Curl error', ['error' => $error]);
                return [
                    'success' => false,
                    'message' => 'Curl error: ' . $error,
                ];
            }

            $result = json_decode($response, true);

            // Log raw response for debugging
            Log::info('Fonnte API Response', ['phone' => $phone, 'response' => $result, 'http_code' => $httpCode]);

            if ($httpCode >= 200 && $httpCode < 300 && isset($result['status']) && $result['status'] === true) {
                return [
                    'success' => true,
                    'message' => 'Message sent successfully',
                    'data' => $result,
                ];
            }

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
     * Generate Invoice PDF and return public URL
     */
    public function generateInvoicePdf(Order $order): ?string
    {
        try {
            // Load the order with package relationship
            $order->load('package');

            // Generate PDF
            $pdf = Pdf::loadView('pdf.invoice', ['order' => $order]);

            // Generate unique filename
            $filename = 'invoices/invoice-' . $order->order_number . '.pdf';

            // Store in public disk
            Storage::disk('public')->put($filename, $pdf->output());

            // Return public URL
            $url = config('app.url') . '/storage/' . $filename;

            Log::info('Invoice PDF generated', [
                'order_number' => $order->order_number,
                'url' => $url,
            ]);

            return $url;
        } catch (\Exception $e) {
            Log::error('Failed to generate invoice PDF', [
                'order_number' => $order->order_number,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Send invoice message with PDF attachment
     */
    public function sendInvoice(
        string $phone,
        string $customerName,
        string $orderNumber,
        string $packageName,
        int $amount,
        string $paymentDate,
        ?Order $order = null
    ): array {
        $formattedAmount = 'Rp ' . number_format($amount, 0, ',', '.');
        $dashboardUrl = config('app.url') . '/lembaga';

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
        $message .= "🔗 *Akses Dashboard:*\n";
        $message .= "{$dashboardUrl}\n\n";

        // Generate PDF invoice if order is provided
        $pdfUrl = null;
        $pdfFilename = null;

        if ($order) {
            $pdfUrl = $this->generateInvoicePdf($order);
            $pdfFilename = "Invoice-{$orderNumber}.pdf";

            // Add download link fallback
            if ($pdfUrl) {
                $message .= "📄 *Download Invoice:*\n";
                $message .= "{$pdfUrl}\n\n";
            }
        }

        $message .= "Jika ada pertanyaan, silakan hubungi kami.\n\n";
        $message .= "Salam hangat,\n";
        $message .= "*Tim SertiKu* 💙";

        return $this->send($phone, $message, $pdfUrl, $pdfFilename);
    }
}
