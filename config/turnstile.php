<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cloudflare Turnstile Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk Cloudflare Turnstile CAPTCHA
    | Dapatkan key gratis di: https://dash.cloudflare.com → Turnstile
    |
    */

    'site_key' => env('TURNSTILE_SITE_KEY', ''),
    'secret_key' => env('TURNSTILE_SECRET_KEY', ''),

    // Enable/disable Turnstile (useful for local development)
    'enabled' => env('TURNSTILE_ENABLED', false),
];
