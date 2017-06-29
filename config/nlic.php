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
        'base_url' => env('NLIC_CONTEXT_BASE_URL', 'https://go.netlicensing.io/core/v2/rest')
    ],
    'auth' => [
        'username' => env('NLIC_AUTH_USERNAME', 'demo'),
        'password' => env('NLIC_AUTH_PASSWORD', 'demo'),
        'api_key' => env('NLIC_AUTH_API_KEY'),
    ],
    'defaults' => [
        'use_api_key_for_validation_and_token' => env('NLIC_DEFAULTS_USE_API_KEY_FOR_VALIDATION_AND_TOKEN', false)
    ]
];
