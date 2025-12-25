<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnterpriseFormMail;

class EnterpriseController extends Controller
{
    /**
     * Admin email for enterprise form notifications
     */
    private const ADMIN_EMAIL = 'sertikuofficial@gmail.com';

    /**
     * Get n8n Webhook URL for Enterprise form (from .env)
     */
    private function getN8nEnterpriseWebhookUrl(): string
    {
        return env('N8N_ENTERPRISE_WEBHOOK_URL', '');
    }

    /**
     * Handle enterprise form submission
     * Sends data to n8n webhook for Google Spreadsheet storage
     * AND sends email notification to admin
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'institution' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $enterpriseData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            // Prefix with ' to force Google Sheets to treat as text (preserves leading 0)
            'phone' => "'" . $validated['phone'],
            'institution' => $validated['institution'],
            'message' => $validated['message'],
            'source' => 'enterprise_form',
            'timestamp' => now()->toIso8601String(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ];

        // 1. Send to n8n webhook (for spreadsheet)
        $this->sendToN8n($enterpriseData);

        // 2. Send email notification to admin
        $this->sendEmailNotification($enterpriseData);

        // 3. Log activity
        try {
            \App\Models\ActivityLog::log(
                'enterprise_form',
                'Permintaan Enterprise baru: ' . $validated['institution'],
                null,
                [
                    'email' => $validated['email'],
                    'name' => $validated['name'],
                    'institution' => $validated['institution']
                ]
            );
        } catch (\Exception $e) {
            Log::error('Failed to log enterprise activity', ['error' => $e->getMessage()]);
        }

        // Always show success to user
        return back()->with('success', 'Permintaan Anda berhasil dikirim! Tim kami akan menghubungi Anda dalam 1x24 jam.');
    }

    /**
     * Send enterprise data to n8n webhook
     */
    private function sendToN8n(array $data): void
    {
        $webhookUrl = $this->getN8nEnterpriseWebhookUrl();

        if (empty($webhookUrl)) {
            Log::warning('N8N_ENTERPRISE_WEBHOOK_URL not configured in .env');
            return;
        }

        try {
            $response = Http::timeout(10)->post($webhookUrl, $data);

            if ($response->successful()) {
                Log::info('Enterprise form sent to n8n', ['email' => $data['email']]);
            } else {
                Log::warning('n8n enterprise webhook failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('n8n enterprise webhook error', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send email notification to admin
     */
    private function sendEmailNotification(array $data): void
    {
        try {
            Mail::to(self::ADMIN_EMAIL)->send(new EnterpriseFormMail($data));
            Log::info('Enterprise email sent to admin', ['from' => $data['email']]);
        } catch (\Exception $e) {
            Log::error('Failed to send enterprise email', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
