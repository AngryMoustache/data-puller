<?php

namespace App\Enums;

use Api\Clients;

enum Origin: string
{
    case TWITTER = 'twitter';
    case DEVIANTART = 'deviant-art';

    public function label()
    {
        return match ($this) {
            self::TWITTER => 'Twitter',
            self::DEVIANTART => 'DeviantArt',
        };
    }

    public function icon()
    {
        return match ($this) {
            self::TWITTER => 'fab fa-twitter',
            self::DEVIANTART => 'fab fa-deviantart',
        };
    }

    public static function list()
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($value) => [$value->value => $value->name]);
    }

    public function style()
    {
        return match ($this) {
            self::DEVIANTART => 'background: #00e59b !important; color: #3b3b3b !important;',
            self::TWITTER => 'background: #1da1f2 !important;',
        };
    }
}
