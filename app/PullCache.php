<?php

namespace App;

use App\Models\Pull;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PullCache
{
    const KEY = 'pull-cache';

    public function build(): Collection
    {
        return Cache::rememberForever(static::KEY, function () {
            return Pull::online()
                ->with('tags', 'origin')
                ->get()
                ->mapWithKeys(fn (Pull $pull) => [$pull->id => collect([
                    'name' => $pull->name,
                    'slug' => $pull->slug,
                    'artist' => $pull->artist,
                    'source_url' => $pull->source_url,
                    'views' => $pull->views,
                    'verdict_at' => $pull->verdict_at,
                    'origin' => $pull->origin ? [
                        'id' => $pull->origin->id,
                        'name' => $pull->origin->name,
                        'slug' => $pull->origin->slug,
                    ] : null,
                    'tags' => $pull->tags->map(fn ($tag) => [
                        'id' => $tag->id,
                        'name' => $tag->name,
                        'slug' => $tag->slug,
                    ]),
                ])]);
        });
    }

    public function rebuild()
    {
        Cache::forget(static::KEY);
        $this->build();
    }

    public function get(): Collection
    {
        return Collection::wrap(Cache::get(static::KEY) ?? $this->build());
    }
}
