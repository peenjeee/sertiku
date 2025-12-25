<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    /**
     * Admin email for contact form notifications
     */
    private const ADMIN_EMAIL = 'sertikuofficial@gmail.com';

    /**
     * Get n8n Webhook URL for Contact form (from .env)
     */
    private function getN8nContactWebhookUrl(): string
    {
        return env('N8N_CONTACT_WEBHOOK_URL', '');
    }

    /**
     * Handle contact form submission
     * Sends message to n8n webhook for Google Spreadsheet storage
     * AND sends email notification to admin
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $contactData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'source' => 'contact_form',
            'timestamp' => now()->toIso8601String(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ];

        // 1. Send to n8n webhook (for spreadsheet)
        $this->sendToN8n($contactData);

        // 2. Send email notification to admin
        $this->sendEmailNotification($contactData);

        // Always show success to user
        return back()->with('success', 'Pesan Anda berhasil dikirim! Kami akan merespon secepatnya.');
    }

    /**
     * Send contact data to n8n webhook
     */
    private function sendToN8n(array $data): void
    {
        $webhookUrl = $this->getN8nContactWebhookUrl();

        if (empty($webhookUrl)) {
            Log::warning('N8N_CONTACT_WEBHOOK_URL not configured in .env');
            return;
        }

        try {
            $response = Http::timeout(10)->post($webhookUrl, $data);

            if ($response->successful()) {
                Log::info('Contact form sent to n8n', ['email' => $data['email']]);
            } else {
                Log::warning('n8n contact webhook failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('n8n contact webhook error', [
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
            Mail::to(self::ADMIN_EMAIL)->send(new ContactFormMail($data));
            Log::info('Contact email sent to admin', ['from' => $data['email']]);
        } catch (\Exception $e) {
            Log::error('Failed to send contact email', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}

