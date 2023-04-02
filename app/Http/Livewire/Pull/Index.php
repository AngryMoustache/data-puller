<?php

namespace App\Http\Livewire\Pull;

use App\Enums\Sorting;
use App\Http\Livewire\Traits\HasPagination;
use App\Http\Livewire\Traits\HasPreLoading;
use App\Models\Origin;
use App\Models\Pull;
use App\Models\Tag;
use App\Pulls;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use Livewire\Component;

class Index extends Component
{
    use HasPreLoading;
    use HasPagination;

    public int $perPage = 20;

    public Collection $selectedTags;

    public null | string $artist = null;

    public Sorting $sort = Sorting::NEWEST;

    public null | int $randomizer = null;

    public null | Origin $origin = null;

    public function mount(string $filterString = '')
    {
        $filters = collect(explode('/', $filterString))
            ->map(fn ($filter) => explode(':', $filter))
            ->filter(fn ($filter) => count($filter) > 1)
            ->mapWithKeys(fn ($filter) => [$filter[0] => $filter[1]]);

        $this->randomizer = $filters->get('randomizer', 0);
        $this->origin = Origin::whereSlug($filters->get('origin'))->first();
        $this->sort = Sorting::tryFrom($filters['sort'] ?? '') ?? Sorting::default();
        $this->selectedTags = Tag::whereIn('slug', explode(',', $filters->get('tags', '')))->get();
        $this->artist = $filters->get('artist', null);
    }

    public function render()
    {
        if (! $this->loaded) {
            return $this->renderLoadingGridContainer(15);
        }

        // Set the randomizer seed
        srand($this->randomizer);

        // Update the URL
        $this->dispatchBrowserEvent('update-browser-url', [
            'url' => route('pull.index', $this->buildQueryString()),
        ]);

        // Get the pulls
        $pulls = Pulls::make()
            ->when($this->selectedTags->isNotEmpty(), fn ($items) => $items->filter(function ($pull) {
                return collect($pull['tags'])
                    ->pluck('slug')
                    ->intersect($this->selectedTags->pluck('slug'))
                    ->count();
            }))
            ->when($this->origin, fn ($items) => $items->where('origin', $this->origin->slug))
            ->when($this->sort, fn ($items) => $this->sort->sortCollection($items))
            ->when($this->artist, fn ($items) => $items->where('artist', $this->artist));

        return view('livewire.pull.index', [
            'pulls' => $pulls->take($this->page * $this->perPage)->fetch(),
            'count' => $pulls->count(),
            'hasMore' => $pulls->count() > $this->page * $this->perPage,
            'sortOptions' => Sorting::list(),
            'origins' => Origin::get()->mapWithKeys(function ($origin) {
                return [$origin->slug => view('components.origin', ['origin' => $origin])->render()];
            }),
        ]);
    }

    public function setFilterValues($sort, $origin)
    {
        $this->sort = Sorting::from($sort);

        if ($this->sort->isRandomizer()) {
            $this->randomizer = now()->timestamp;
        } else {
            $this->randomizer = 0;
        }

        $this->origin = Origin::whereSlug($origin)->first();
    }

    private function buildQueryString()
    {
        return collect([
            // 'query' => $this->query,
            'sort' => $this->sort->value,
            'randomizer' => $this->randomizer,
            'origin' => $this->origin?->slug,
            'tags' => $this->selectedTags->pluck('slug')->join(','),
            'artist' => $this->artist,
        ])
            ->filter()
            ->reject(fn ($value) => in_array($value, [ // Default values
                Sorting::default()->value,
            ]))
            ->map(fn ($value, $key) => "{$key}:{$value}")
            ->implode('/');
    }
}
