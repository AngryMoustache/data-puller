<?php

namespace App\Http\Livewire\Wireables;

use App\Enums\Display;
use App\Enums\Sorting;
use App\Models\Pull;
use App\Models\Tag;
use Illuminate\Support\Collection;
use Livewire\Wireable;

class FilterBag implements Wireable
{
    public Collection $filters;

    public Sorting $sort = Sorting::POPULAR;
    public Display $display = Display::CARD;

    public function __construct($filters = '')
    {
        $tags = Tag::fullTagList();

        $this->filters = collect(explode('/', $filters))
            ->map(fn ($filter) => explode(':', $filter))
            ->filter(fn ($filter) => count($filter) > 1)
            ->mapWithKeys(function ($filter) use ($tags) {
                if ($filter[0] === 'tags') {
                    $value = collect(explode(',', $filter[1]))
                        ->map(fn ($tag) => $tags->where('fullSlug', $tag)->first())
                        ->filter(fn ($tag) => $tag->id)
                        ->values();
                }

                $value ??= explode(',', $filter[1] ?? null);

                return [$filter[0] => $value];
            });

        // Filter defaults
        $this->filters = $this->filters->union([
            'tags' => collect(),
        ]);
    }

    public function tags()
    {
        return collect($this->filters['tags'] ?? []);
    }

    public function toggleTag($newTag)
    {
        $newTag = (object) $newTag;

        if ($this->filters['tags']->contains('fullSlug', $newTag->fullSlug)) {
            $this->filters['tags'] = $this->filters['tags']->reject(function ($tag) use ($newTag) {
                return $tag->fullSlug === $newTag->fullSlug;
            });
        } else {
            $this->filters['tags']->push($newTag);
        }
    }

    public function pulls()
    {
        return Pull::with('tags')
            // ->online()
            ->when($this->filters['tags']->isNotEmpty(), function ($query) {
                // Tags filter
                foreach ($this->filters['tags'] as $tag) {
                    $query->whereHas('tags', function ($query) use ($tag) {
                        // Some tags have a special extra tag, like a color: shirt=red
                        $query->where('tags.slug', $tag->slug);
                        if (! empty($tag->extraSlug)) {
                            $query->where('data', 'LIKE', '%"' . $tag->extraSlug . '":%');
                        }
                    });
                }
            })
            ->get()
            ->when($this->sort, function ($items) {
                // Sorting
                return $this->sort->sortCollection($items);
            });
    }

    public function toQueryString()
    {
        return $this->filters
            ->reject(fn ($item) => optional($item)->isEmpty() || empty($item))
            ->map(fn ($value, $key) => $key . ':' . collect($value)->pluck('fullSlug')->join(','))
            ->implode('/');
    }

    public static function fromLivewire($value)
    {
        return new static($value);
    }

    public function toLivewire()
    {
        return $this->toQueryString();
    }
}
