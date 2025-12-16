<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google reCAPTCHA Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk Google reCAPTCHA v2
    | Dapatkan key gratis di: https://www.google.com/recaptcha/admin
    |
    */

    'site_key' => env('RECAPTCHA_SITE_KEY', ''),
    'secret_key' => env('RECAPTCHA_SECRET_KEY', ''),

    // Enable/disable reCAPTCHA (useful for local development)
    'enabled' => env('RECAPTCHA_ENABLED', true),
];
