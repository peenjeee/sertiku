<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{
    /**
     * Get n8n Webhook URL for CTA email collection (from .env)
     */
    private function getN8nWebhookUrl(): string
    {
        return env('N8N_LEAD_WEBHOOK_URL', '');
    }

    /**
     * Get n8n Webhook URL for Status notification subscription (from .env)
     */
    private function getN8nStatusWebhookUrl(): string
    {
        return env('N8N_STATUS_WEBHOOK_URL', '');
    }

    /**
     * Handle CTA form submission from landing page
     * Sends email to n8n webhook for Google Spreadsheet storage
     */
    public function subscribeCta(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $webhookUrl = $this->getN8nWebhookUrl();

        // If no webhook URL configured, just return success
        if (empty($webhookUrl)) {
            Log::warning('N8N_LEAD_WEBHOOK_URL not configured in .env');
            return response()->json([
                'success' => true,
                'message' => 'Email berhasil didaftarkan!',
            ]);
        }

        try {
            // Send email to n8n webhook
            $response = Http::timeout(10)->post($webhookUrl, [
                'email' => $validated['email'],
                'source' => 'landing_cta',
                'timestamp' => now()->toIso8601String(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Email berhasil didaftarkan!',
                ]);
            } else {
                Log::warning('n8n webhook failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                // Still return success to user (don't block registration)
                return response()->json([
                    'success' => true,
                    'message' => 'Email berhasil didaftarkan!',
                ]);
            }
        } catch (\Exception $e) {
            Log::error('n8n webhook error', [
                'error' => $e->getMessage(),
            ]);

            // Still return success to user (don't block registration)
            return response()->json([
                'success' => true,
                'message' => 'Email berhasil didaftarkan!',
            ]);
        }
    }

    /**
     * Handle Status notification subscription from status page
     * Sends email to n8n webhook for Google Spreadsheet storage
     */
    public function subscribeStatus(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $webhookUrl = $this->getN8nStatusWebhookUrl();

        // If no webhook URL configured, just return success
        if (empty($webhookUrl)) {
            Log::warning('N8N_STATUS_WEBHOOK_URL not configured in .env');
            return response()->json([
                'success' => true,
                'message' => 'Berhasil subscribe notifikasi status!',
            ]);
        }

        try {
            // Send email to n8n webhook
            $response = Http::timeout(10)->post($webhookUrl, [
                'email' => $validated['email'],
                'source' => 'status_notification',
                'timestamp' => now()->toIso8601String(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil subscribe notifikasi status!',
                ]);
            } else {
                Log::warning('n8n status webhook failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil subscribe notifikasi status!',
                ]);
            }
        } catch (\Exception $e) {
            Log::error('n8n status webhook error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil subscribe notifikasi status!',
            ]);
        }
    }
}
