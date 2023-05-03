<?php

namespace App;

use App\Models\Origin;
use App\Models\Pull;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Pulls extends Collection
{
    const KEY = 'pull-cache';

    public null | int $limit = null;

    public static function make($items = [])
    {
        session(['pulls-with-prompts' => false]);

        return new static(self::build()->toArray());
    }

    public function fetch(): Collection
    {
        $promptOrigins = Origin::prompts()->pluck('id');

        return Pull::query()
            ->orderByRaw('FIELD(id, ' . $this->pluck('id')->implode(',') . ')')
            ->when(! $this->hasPrompts(), fn ($query) => $query->whereNotIn('origin_id', $promptOrigins))
            ->unless(is_null($this->limit), fn ($query) => $query->limit($this->limit))
            ->find($this->pluck('id'));
    }

    public function limit(null | int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function withPrompts(bool $withPrompts = true): self
    {
        session(['pulls-with-prompts' => $withPrompts]);

        return $this;
    }

    public function hasPrompts(): bool
    {
        return session('pulls-with-prompts', false);
    }

    public static function build(bool $rebuild = false): Collection
    {
        if ($rebuild) {
            Cache::forget(static::KEY);
        }

        return Cache::rememberForever(static::KEY, function () {
            return static::getCacheData();
        });
    }

    public static function getCacheData(): Collection
    {
        return Pull::online()
            ->with('tags', 'origin', 'artist')
            ->get()
            ->filter(fn ($pull) => $pull->attachment)
            ->map(function (Pull $pull) {
                return collect([
                    'id' => $pull->id,
                    'name' => $pull->name,
                    'views' => $pull->views,
                    'verdict_at' => $pull->verdict_at,
                    'artists' => [$pull->artist?->slug],
                    'origins' => [$pull->origin?->slug],
                    'tags' => $pull->tags->pluck('slug'),
                    'folders' => $pull->folders->pluck('slug'),
                ]);
            });
    }
}
