<?php

namespace App\Enums;

enum MediaType: string
{
    case IMAGE = 'image';
    case VIDEO = 'video';

    public static function guess($key)
    {
        return match ($key) {
            // Images
            'image', 'photo' => self::IMAGE,
            'animated_gif', 'video' => self::VIDEO,
        };
    }
}
