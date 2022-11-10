<?php

namespace App\Resources;

use App\Models\Tag;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class JsonTag
{
    public static function collection($tags)
    {
        $tags = $tags->map(function ($tag) {
            $data = $tag?->pivot?->data;
            if (! $data) {
                return $tag;
            }

            return collect(json_decode($data))->map(function ($extra, $slug) use ($tag) {
                $_tag = clone $tag;
                $_tag->extra = $extra;
                $_tag->extra_slug = $slug;

                return $_tag;
            });
        })->flatten();

        return Collection::wrap($tags)->map(fn ($tag) => static::single($tag));
    }

    public static function single($tag)
    {
        return (object) [
            'id' => $tag->id,
            'name' => $tag->name,
            'slug' => $tag->slug,
            'fullSlug' => $tag->slug . ($tag->extra_slug ? '=' . $tag->extra_slug : ''),
            'extra' => $tag->extra,
            'extraSlug' => $tag->extra_slug,
        ];
    }
}
