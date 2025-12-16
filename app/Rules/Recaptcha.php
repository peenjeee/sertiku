<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Skip validation if reCAPTCHA is disabled
        if (!config('recaptcha.enabled')) {
            return;
        }

        // Check if secret key is configured
        if (empty(config('recaptcha.secret_key'))) {
            return; // Skip if not configured
        }

        // Verify with Google
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('recaptcha.secret_key'),
            'response' => $value,
            'remoteip' => request()->ip(),
        ]);

        if (!$response->successful()) {
            $fail('Gagal memverifikasi reCAPTCHA. Silakan coba lagi.');
            return;
        }

        $body = $response->json();

        if (!isset($body['success']) || !$body['success']) {
            $fail('Verifikasi reCAPTCHA gagal. Pastikan Anda bukan robot.');
        }
    }
}
