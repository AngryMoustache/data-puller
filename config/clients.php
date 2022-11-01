<?php

return [
    'twitter' => [
        'api_key' => env('TWITTER_API_KEY'),
        'secret_key' => env('TWITTER_SECRET_KEY'),
        'bearer_token' => env('TWITTER_BEARER_TOKEN'),
        'user_id' => env('TWITTER_USER_ID'),
    ],
    'deviant-art' => [
        'folder-id' => env('DEVIANT_ART_FOLDER_ID'),
        'client-id' => env('DEVIANT_ART_CLIENT_ID'),
        'client-secret' => env('DEVIANT_ART_CLIENT_SECRET'),
    ],
];
