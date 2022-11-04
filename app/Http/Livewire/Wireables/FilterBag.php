<?php

namespace App\Http\Livewire\Wireables;

use App\Models\Pull;
use Illuminate\Support\Collection;
use Livewire\Wireable;

class FilterBag extends CollectionBag implements Wireable
{
    public Collection $filters;

    public function __construct($filters = '')
    {
        $this->filters = collect(explode('/', $filters))
            ->map(fn ($filter) => explode(':', $filter))
            ->filter(fn ($filter) => count($filter) > 1)
            ->mapWithKeys(fn ($filter) => [$filter[0] => explode(',', $filter[1] ?? null)]);
    }

    public function pulls()
    {
        return Pull::with('tags', 'origin')
            ->orderBy('id', 'desc')
            // Origin filter
            ->when(count($this->filters['origins'] ?? []), function ($query) {
                $query->whereHas('origin', function ($query) {
                    $query->whereIn('slug', $this->filters['origins']);
                });
            })
            // Tags filter
            ->when(count($this->filters['tags'] ?? []), function ($query) {
                // Some tags have a special extra tag, like a color: shirt=red
                $tags = collect($this->filters['tags'] ?? [])->map(fn ($tag) => explode('=', $tag));

                foreach ($tags as $tag) {
                    $query->whereHas('tags', function ($query) use ($tag) {
                        $query->where([
                            ['tags.slug', $tag[0]],
                            ['data', 'LIKE', '%' . ($tag[1] ?? '') . '%'],
                        ]);
                    });
                }
            })
            ->get();
    }
}
