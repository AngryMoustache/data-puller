<?php

return [
    'twitter' => [
        'api_key' => env('TWITTER_API_KEY'),
        'secret_key' => env('TWITTER_SECRET_KEY'),
        'bearer_token' => env('TWITTER_BEARER_TOKEN'),
    ],
    'deviant_art' => [
        'client_id' => env('DEVIANT_ART_CLIENT_ID'),
        'client_secret' => env('DEVIANT_ART_CLIENT_SECRET'),
    ],
    'pixiv' => [
        'client_id' => env('PIXIV_CLIENT_ID'),
        'client_secret' => env('PIXIV_CLIENT_SECRET'),
        // 'code' => env('PIXIV_CODE'),
        'access_token' => env('PIXIV_ACCESS_TOKEN'),
        'refresh_token' => env('PIXIV_REFRESH_TOKEN'),
    ],
];
