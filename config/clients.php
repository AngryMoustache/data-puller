<?php

return [
    'twitter' => [
        'api_key' => env('TWITTER_API_KEY'),
        'secret_key' => env('TWITTER_SECRET_KEY'),
        'bearer_token' => env('TWITTER_BEARER_TOKEN'),
    ],
    'deviant-art' => [
        'client-id' => env('DEVIANT_ART_CLIENT_ID'),
        'client-secret' => env('DEVIANT_ART_CLIENT_SECRET'),
    ],
];
