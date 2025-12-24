<?php

return [
    /*
    |--------------------------------------------------------------------------
    | IPFS Configuration (Storacha Network)
    |--------------------------------------------------------------------------
    |
    | Configuration for IPFS decentralized storage using Storacha.
    | Storacha uses UCAN authentication via CLI login.
    |
    | Setup:
    | 1. cd scripts && npm install
    | 2. npx storacha login <your-email>
    | 3. npx storacha space create <space-name>
    |
    */

    'enabled'     => env('IPFS_ENABLED', false),

    'provider'    => 'storacha',

    /*
    |--------------------------------------------------------------------------
    | IPFS Gateway
    |--------------------------------------------------------------------------
    |
    | The gateway URL used to access files stored on IPFS.
    | Storacha gateway: https://w3s.link/ipfs
    |
    */
    'gateway_url' => env('IPFS_GATEWAY_URL', 'https://w3s.link/ipfs'),
];
