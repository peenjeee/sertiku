<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('APP_URL') . '/auth/google/callback',
        // Google Drive Backup
        'refresh_token' => env('GOOGLE_DRIVE_REFRESH_TOKEN'),
        'drive_folder_id' => env('GOOGLE_DRIVE_FOLDER_ID'),
    ],

    'walletconnect' => [
        'project_id' => env('WALLETCONNECT_PROJECT_ID'),
    ],

    'n8n' => [
        'webhook_url' => env('N8N_WEBHOOK_URL'),
        'webhook_report_url' => env('N8N_WEBHOOK_REPORT_URL'),
    ],

    'fonnte' => [
        'token' => env('FONNTE_TOKEN'),
    ],

    'uptimerobot' => [
        'api_key' => env('UPTIMEROBOT_API_KEY'),
    ],

];
