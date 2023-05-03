<?php

use AngryMoustache\Rambo\Resources\Administrator;
use AngryMoustache\Rambo\Resources\Attachment;
use App\Rambo;

return [
    'admin-route' => 'admin',
    'admin-guard' => 'rambo',
    'resources' => [
        Attachment::class,
        Administrator::class,
        Rambo\Pull::class,
        Rambo\Origin::class,
        Rambo\Video::class,
    ],
    'navigation' => [
        'General' => [
            Administrator::class,
        ],
        'Media' => [
            Attachment::class,
            Rambo\Video::class,
        ],
        Rambo\Origin::class,
        Rambo\Pull::class,
    ],
    'cropper' => [
        'formats' => [
            \AngryMoustache\Media\Formats\Thumb::class => 'Thumb',
        ],
    ],
];
