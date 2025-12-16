<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Turnstile implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Skip validation if Turnstile is disabled
        if (!config('turnstile.enabled')) {
            return;
        }

        // Check if secret key is configured
        if (empty(config('turnstile.secret_key'))) {
            return; // Skip if not configured
        }

        // Verify with Cloudflare
        $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => config('turnstile.secret_key'),
            'response' => $value,
            'remoteip' => request()->ip(),
        ]);

        if (!$response->successful()) {
            $fail('Gagal memverifikasi Cloudflare Turnstile. Silakan coba lagi.');
            return;
        }

        $body = $response->json();

        if (!isset($body['success']) || !$body['success']) {
            $fail('Verifikasi Cloudflare Turnstile gagal. Pastikan Anda bukan robot.');
        }
    }
}
