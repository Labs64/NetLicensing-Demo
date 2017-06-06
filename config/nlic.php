<?php

return [
    'cache' => [
        'lifetime' => 90,
    ],
    'history' => [
        'max_items' => 5,
        'lifetime' => 90,
    ],
    'auth' => [
        'username' => env('NLIC_AUTH_USERNAME', 'demo'),
        'password' => env('NLIC_AUTH_PASSWORD', 'demo')
    ]
];
