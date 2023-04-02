<?php

namespace App;

use App\Models\Pull;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Pulls extends Collection
{
    const KEY = 'pull-cache';

    public static function make($items = [])
    {
        return new static(self::build()->toArray());
    }

    public function fetch(): Collection
    {
        return Pull::query()
            ->orderByRaw('FIELD(id, ' . $this->pluck('id')->implode(',') . ')')
            ->find($this->pluck('id'));
    }

    public static function build(bool $rebuild = false): Collection
    {
        if ($rebuild) {
            Cache::forget(static::KEY);
        }

        return Cache::rememberForever(static::KEY, function () {
            return Pull::online()
                ->with('tags', 'origin', 'artist')
                ->get()
                ->filter(fn ($pull) => $pull->attachment)
                ->map(function (Pull $pull) {
                    return collect([
                        'id' => $pull->id,
                        'name' => $pull->name,
                        'artist' => $pull->artist?->slug,
                        'views' => $pull->views,
                        'verdict_at' => $pull->verdict_at,
                        'origin' => $pull->origin?->slug,
                        'tags' => $pull->tags->map(fn ($tag) => [
                            'id' => $tag->id,
                            'name' => $tag->name,
                            'slug' => $tag->slug,
                        ]),
                    ]);
                });
        });
    }
}
