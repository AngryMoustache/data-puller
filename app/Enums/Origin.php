<?php

namespace App\Enums;

use Api\Clients;
use App\Models\Origin as ModelsOrigin;

enum Origin: string
{
    case TWITTER = 'twitter';
    case DEVIANTART = 'deviant-art';
    case PIXIV = 'pixiv';
    case SCRAPER = 'scraper';
    case PROMPT = 'prompt';
    case EXTERNAL = 'external';

    public function label()
    {
        return match ($this) {
            self::TWITTER => 'Twitter',
            self::DEVIANTART => 'DeviantArt',
            self::PIXIV => 'Pixiv',
            self::SCRAPER => 'Scraper',
            self::PROMPT => 'Prompt',
            self::EXTERNAL => 'External',
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
            self::TWITTER => 'fab fa-x-twitter',
            self::DEVIANTART => 'fab fa-deviantart',
            self::PIXIV => 'fab fa-pinterest-p',
            self::SCRAPER => 'fas fa-rss',
            self::PROMPT => 'fas fa-pencil-alt',
            self::EXTERNAL => 'fas fa-upload',
        };
    }

    public function style()
    {
        return match ($this) {
            self::DEVIANTART => 'background: #00e59b !important; color: #000 !important;',
            self::TWITTER => 'background: #1da1f2 !important; color: #fff !important;',
            self::PIXIV => 'background: #166392 !important; color: #fff !important;',
            self::SCRAPER => 'background: #d12b9c !important; color: #fff !important;',
            self::PROMPT => 'background: #f2d41d !important; color: #000 !important;',
            self::EXTERNAL => 'background: #4a5577 !important; color: #fff !important;',
        };
    }

    public function pull(ModelsOrigin $origin)
    {
        return match ($this) {
            self::TWITTER => (new Clients\Twitter($origin))->likes(),
            self::DEVIANTART => (new Clients\DeviantArt($origin))->favorites(),
            self::PIXIV => (new Clients\Pixiv($origin))->bookmarks(),
            self::SCRAPER => (new Clients\Scraper($origin))->favorites(),
            default => null,
        };
    }

    public function canPull(): bool
    {
        return match ($this) {
            self::TWITTER => true,
            self::DEVIANTART => true,
            self::PIXIV => true,
            self::SCRAPER => true,
            default => false,
        };
    }
}
