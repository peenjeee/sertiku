<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Blockchain Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Polygon blockchain integration.
    | Uses Polygon Amoy Testnet by default.
    |
    */

    'enabled'          => env('BLOCKCHAIN_ENABLED', false),

    'rpc_url'          => env('POLYGON_RPC_URL', 'https://rpc-amoy.polygon.technology/'),

    'chain_id'         => env('POLYGON_CHAIN_ID', '80002'),

    'private_key'      => env('POLYGON_PRIVATE_KEY', ''),

    'wallet_address'   => env('POLYGON_WALLET_ADDRESS', ''),

    'contract_address' => env('POLYGON_CONTRACT_ADDRESS', ''),

    'explorer_url'     => env('POLYGON_EXPLORER_URL', 'https://amoy.polygonscan.com'),
];
