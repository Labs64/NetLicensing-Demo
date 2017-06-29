<?php

return [
    'cache' => [
        'lifetime' => 90,
    ],
    'history' => [
        'max_items' => 5,
        'lifetime' => 90,
    ],
    'context' => [
        'base_url' => env('NLIC_BASE_URL', 'https://go.netlicensing.io/core/v2/rest')
    ],
    'auth' => [
        'username' => env('NLIC_AUTH_USERNAME', 'demo'),
        'password' => env('NLIC_AUTH_PASSWORD', 'demo'),
        'api_key' => env('NLIC_AUTH_API_KEY'),
    ],
    'defaults' => [
        'use_api_key' => env('NLIC_USE_API_KEY', false)
    ]
];
