<?php

namespace App;

use AngryMoustache\Media\Models\Attachment;
use App\Enums\MediaType;
use App\Livewire\Wireables\FilterBag;
use App\Models\Origin;
use App\Models\Pull;
use App\Models\Tag;
use App\Models\TagGroup;
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

    public function fetch(null | FilterBag $filterBag = null): Collection
    {
        $promptOrigins = Origin::prompts()->pluck('id');

        return Pull::query()
            ->orderByRaw('FIELD(id, ' . $this->pluck('id')->implode(',') . ')')
            ->when(! $this->hasPrompts(), fn ($query) => $query->whereNotIn('origin_id', $promptOrigins))
            ->unless(is_null($this->limit), fn ($query) => $query->limit($this->limit))
            ->find($this->pluck('id'))
            ->when($filterBag, fn (Collection $pulls) => $pulls->map(function (Pull $pull) use ($filterBag) {
                $tags = $filterBag->filters
                    ->filter(fn ($filter) => $filter->type === Tag::class)
                    ->pluck('id');

                $thumbnailTags = collect($pull->thumbnails)
                    ->map(fn (array $thumbnail) => collect($thumbnail['tags'])
                        ->flatten()
                        ->mapWithKeys(fn (int $tag) => [$tag => $thumbnail['thumbnail_url']])
                        ->toArray()
                    )
                    ->mapWithKeys(fn (array $tag) => $tag);

                // Check if we are filtering for a specific thumbnail
                foreach ($tags as $tag) {
                    if ($thumbnailTags[$tag] ?? false) {
                        $pull->thumbnail = $thumbnailTags[$tag];

                        return $pull;
                    }
                }

                // Check if there is a main thumbnail
                $mainThumbnail = collect($pull->thumbnails)->first(fn (array $thumbnail) => $thumbnail['is_main']);
                if ($mainThumbnail) {
                    $pull->thumbnail = $mainThumbnail['thumbnail_url'];
                }

                return $pull;
            }));
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
            ->with('tagGroups.tags', 'origin', 'artist')
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
                    'tags' => self::getCachedTagsForPull($pull),
                    'thumbnails' => $pull->thumbnails ?? [],
                    'folders' => $pull->folders->pluck('slug'),
                    'media_type' => [
                        MediaType::IMAGE->value => $pull->attachments->isEmpty(),
                        MediaType::VIDEO->value => $pull->videos->isEmpty(),
                    ],
                ]);
            });
    }

    private static function getCachedTagsForPull(Pull $pull): Collection
    {

        $groups = $pull->tagGroups->map(fn (TagGroup $group) => [
            'id' => $group->id,
            'is_main' => $group->is_main ?? false,
            'tags' => $group->tags->pluck('slug', 'id'),
        ]);

        $mainTags = $groups->filter(fn ($group) => $group['is_main'])
            ->pluck('tags')
            ->mapWithKeys(fn ($i) => $i);

        // Add the main tags to the other groups
        return $groups->map(function ($group) use ($mainTags) {
            if (! $group['is_main']) {
                $group['tags'] = $group['tags']->merge($mainTags);
            }

            return $group;
        });
    }
}
