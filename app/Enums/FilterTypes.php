<?php

namespace App\Enums;

use App\Models;

enum FilterTypes: string
{
    case QUERY = 'query';
    case TAG = Models\Tag::class;
    case FOLDER = Models\Folder::class;
    case ARTIST = Models\Artist::class;
    case ORIGIN = Models\Origin::class;
    case MEDIATYPE = MediaType::class;

    public static function fromString(string $type): null | string
    {
        return match ($type) {
            'query' => self::QUERY->value,
            'tags' => self::TAG->value,
            'folders' => self::FOLDER->value,
            'artists' => self::ARTIST->value,
            'origins' => self::ORIGIN->value,
            'media-type' => self::MEDIATYPE->value,
            default => null,
        };
    }

    public static function fromClass(string $class): null | string
    {
        return match ($class) {
            self::QUERY->value => 'query',
            self::TAG->value => 'tags',
            self::FOLDER->value => 'folders',
            self::ARTIST->value => 'artists',
            self::ORIGIN->value => 'origins',
            self::MEDIATYPE->value => 'media-type',
            default => null,
        };
    }
}
