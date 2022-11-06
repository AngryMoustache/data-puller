<?php

namespace App\Resources;

use Illuminate\Support\Collection;

class JsonTag
{
    public static function collection($tags)
    {
        return Collection::wrap($tags)->map(fn ($tag) => static::single($tag));
    }

    public static function single($tag)
    {
        return (object) [
            'id' => $tag->id,
            'name' => $tag->name,
            'slug' => $tag->slug,
            'extra' => implode(', ', json_decode($tag->pivot?->data ?? '[]')),
        ];
    }
}
