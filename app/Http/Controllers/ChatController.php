<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    /**
     * Handle chat message and forward to n8n webhook
     */
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'role' => 'nullable|string|in:user,lembaga',
        ]);

        $message = $request->input('message');
        $role = $request->input('role', 'user');
        $userId = auth()->id();

        // Check if n8n webhook is configured
        $webhookUrl = config('services.n8n.webhook_url');

        if (empty($webhookUrl)) {
            return response()->json([
                'success' => true,
                'reply' => $this->getFallbackResponse($message, $role),
            ]);
        }

        try {
            // Send to n8n webhook
            $response = Http::timeout(30)->post($webhookUrl, [
                'message' => $message,
                'role' => $role,
                'user_id' => $userId,
                'context' => $this->getContextForRole($role),
            ]);

            if ($response->successful()) {
                $reply = $response->json('reply') ?? $response->json('output') ?? $response->body();

                return response()->json([
                    'success' => true,
                    'reply' => $reply,
                ]);
            }

            // Fallback response if n8n fails
            return response()->json([
                'success' => true,
                'reply' => $this->getFallbackResponse($message, $role),
            ]);

        } catch (\Exception $e) {
            Log::error('Chat error: ' . $e->getMessage());

            return response()->json([
                'success' => true,
                'reply' => $this->getFallbackResponse($message, $role),
            ]);
        }
    }

    /**
     * Get context for AI based on user role
     */
    private function getContextForRole($role)
    {
        if ($role === 'lembaga') {
            return 'Pengguna adalah lembaga/institusi yang menerbitkan sertifikat digital. Bantu mereka dengan pertanyaan tentang: upload sertifikat, template sertifikat, verifikasi sertifikat, manajemen sertifikat, dan fitur-fitur platform SertiKu.';
        }

        return 'Pengguna adalah penerima sertifikat digital. Bantu mereka dengan pertanyaan tentang: melihat sertifikat mereka, verifikasi sertifikat, share sertifikat ke LinkedIn/media sosial, dan cara menggunakan QR Code.';
    }

    /**
     * Get fallback response when n8n is not available
     */
    private function getFallbackResponse($message, $role)
    {
        $message = strtolower($message);

        // Check for common keywords
        if (str_contains($message, 'verifikasi') || str_contains($message, 'cek')) {
            return 'Untuk verifikasi sertifikat, buka halaman Verifikasi dan masukkan kode hash atau nomor sertifikat (format: SERT-XXXXXX). Anda juga bisa scan QR Code pada sertifikat!';
        }

        if (str_contains($message, 'upload') || str_contains($message, 'buat')) {
            return 'Untuk membuat sertifikat baru, buka menu "Terbitkan Sertifikat", pilih template, isi data penerima, lalu klik "Terbitkan". Sertifikat akan otomatis mendapat nomor unik dan QR Code.';
        }

        if (str_contains($message, 'template')) {
            return 'Untuk mengelola template, buka menu "Upload Sertifikat" untuk upload template baru atau "Galeri Sertifikat" untuk melihat template yang sudah ada.';
        }

        if (str_contains($message, 'qr') || str_contains($message, 'kode')) {
            return 'Setiap sertifikat otomatis mendapat QR Code untuk verifikasi instan. QR Code bisa discan dengan HP untuk mengecek keaslian sertifikat.';
        }

        if (str_contains($message, 'hubungi') || str_contains($message, 'kontak') || str_contains($message, 'admin')) {
            return '📞 Hubungi kami: Email support@sertiku.web.id atau WhatsApp +62 857-7741-9874. Jam operasional: Senin-Jumat, 09:00-17:00 WIB.';
        }

        // Default response
        return 'Terima kasih atas pertanyaan Anda! 😊 Untuk bantuan lebih lanjut, silakan hubungi admin kami melalui email support@sertiku.web.id atau pilih topik bantuan di atas.';
    }
}
