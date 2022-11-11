<?php

namespace App\Enums;

use Api\Clients;
use App\Models\Origin as ModelsOrigin;

enum Origin: string
{
    case TWITTER = 'twitter';
    case DEVIANTART = 'deviant-art';
    case PIXIV = 'pixiv';

    public function label()
    {
        return match ($this) {
            self::TWITTER => 'Twitter',
            self::DEVIANTART => 'DeviantArt',
            self::PIXIV => 'Pixiv',
        };
    }

    public function icon()
    {
        return match ($this) {
            self::TWITTER => 'fab fa-twitter',
            self::DEVIANTART => 'fab fa-deviantart',
            self::PIXIV => 'fab fa-pintrest',
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
            self::PIXIV => 'background: #166392 !important;',
        };
    }

    public function pull(ModelsOrigin $origin)
    {
        return match ($this) {
            self::TWITTER => (new Clients\Twitter($origin))->likes(),
            self::DEVIANTART => (new Clients\DeviantArt($origin))->favorites(),
            self::PIXIV => (new Clients\Pixiv($origin))->bookmarks(),
        };
    }
}
