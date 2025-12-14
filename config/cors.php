<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_origins' => [
        'http://localhost',
        'http://localhost:5174',
    ],

    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'allowed_methods' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,

];
