<?php

namespace App\Livewire\Pull;

use App\Enums\Sorting;
use App\Livewire\Traits\HasPagination;
use App\Livewire\Traits\HasPreLoading;
use App\Livewire\Wireables\FilterBag;
use App\Models\Origin;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    use HasPreLoading;
    use HasPagination;

    public int $perPage = 18;

    public FilterBag $filters;

    public function mount(string $filterString = '')
    {
        $this->filters = new FilterBag($filterString);

        if (Str::contains($filterString, 'page:')) {
            $this->page = (int) Str::between($filterString, 'page:', '/') ?? 1;
        }
    }

    public function render()
    {
        app('site')->title('Gallery');

        if (! $this->loaded) {
            return $this->renderLoadingGridContainer($this->page * 18);
        }

        // Build the query string
        $queryString = $this->filters->buildQueryString(
            extra: ($this->page > 1) ? 'page:' . $this->page : ''
        );

        // Update the URL
        $this->dispatch('update-browser-url', [
            'url' => route('pull.index', $queryString),
        ]);

        // Get the pulls
        $pulls = $this->filters->toPulls();

        return view('livewire.pull.index', [
            'count' => $pulls->count(),
            'hasMore' => $pulls->count() > ($this->page * $this->perPage),
            'sortOptions' => Sorting::list(),
            'origins' => Origin::get()->mapWithKeys(function ($origin) {
                return [$origin->slug => view('components.origin', ['origin' => $origin])->render()];
            }),
            'pulls' => $pulls->limit($this->page * $this->perPage)->fetch(
                $this->filters
            ),
        ]);
    }

    public function setFilterValues($sort, $origins, $mediaType)
    {
        $this->filters->setSorting($sort);
        $this->filters->setOrigins($origins);
        $this->filters->setMediaType($mediaType);

        $this->page = 1;
    }

    #[On('toggleFilter')]
    public function toggleFilter(string $type, $id)
    {
        $this->filters->toggleFilter($type, $id);

        $this->page = 1;
    }
}
