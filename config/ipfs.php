<?php

return [
    /*
    |--------------------------------------------------------------------------
    | IPFS Configuration (Pinata)
    |--------------------------------------------------------------------------
    |
    | Configuration for IPFS decentralized storage using Pinata.
    | Pinata provides a simple HTTP API for pinning files to IPFS.
    |
    | Setup:
    | 1. Sign up at https://pinata.cloud
    | 2. Create API Key (JWT)
    | 3. Add PINATA_JWT to .env
    |
    */

    'enabled' => env('IPFS_ENABLED', false),

    'provider' => 'pinata',

    /*
    |--------------------------------------------------------------------------
    | Pinata JWT Token
    |--------------------------------------------------------------------------
    |
    | JWT token from Pinata API Keys page.
    | Get it from: https://app.pinata.cloud/developers/api-keys
    |
    */
    'pinata_jwt' => env('PINATA_JWT', ''),

    /*
    |--------------------------------------------------------------------------
    | IPFS Gateway
    |--------------------------------------------------------------------------
    |
    | The gateway URL used to access files stored on IPFS.
    | Pinata gateway: https://gateway.pinata.cloud/ipfs
    | Public gateway: https://ipfs.io/ipfs
    |
    */
    'gateway_url' => env('IPFS_GATEWAY_URL', 'https://gateway.pinata.cloud/ipfs'),
];
