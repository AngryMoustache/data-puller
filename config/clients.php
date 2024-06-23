<?php

return [
    'twitter' => [
        'api_key' => env('TWITTER_API_KEY'),
        'secret_key' => env('TWITTER_SECRET_KEY'),
        'bearer_token' => env('TWITTER_BEARER_TOKEN'),
    ],
    'bluesky' => [
        'username' => env('BLUESKY_USERNAME'),
        'password' => env('BLUESKY_PASSWORD'),
    ],
    'deviant_art' => [
        'client_id' => env('DEVIANT_ART_CLIENT_ID'),
        'client_secret' => env('DEVIANT_ART_CLIENT_SECRET'),
    ],
    'pixiv' => [
        // 'code' => env('PIXIV_CODE'),
        'client_id' => env('PIXIV_CLIENT_ID'),
        'client_secret' => env('PIXIV_CLIENT_SECRET'),
        'access_token' => env('PIXIV_ACCESS_TOKEN'),
        'refresh_token' => env('PIXIV_REFRESH_TOKEN'),
    ],
    'scraper' => [
        'base_url' => env('SCRAPER_BASE_URL'),
        'detail_url' => env('SCRAPER_DETAIL_URL'),
        'source_url' => env('SCRAPER_SOURCE_URL'),
    ],
    'open-router' => [
        'api_key' => env('OPEN_ROUTER_API_KEY'),
    ],
];
