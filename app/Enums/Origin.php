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

    public static function list()
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($value) => [$value->value => $value->name]);
    }

    public function icon()
    {
        return match ($this) {
            self::TWITTER => 'fab fa-twitter',
            self::DEVIANTART => 'fab fa-deviantart',
            self::PIXIV => 'fab fa-pinterest-p',
        };
    }

    public function style()
    {
        return match ($this) {
            self::DEVIANTART => 'background: #00e59b !important; color: #000 !important;',
            self::TWITTER => 'background: #1da1f2 !important; color: #fff !important;',
            self::PIXIV => 'background: #166392 !important; color: #fff !important;',
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
