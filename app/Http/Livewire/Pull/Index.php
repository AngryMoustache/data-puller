<?php

namespace App\Http\Livewire\Pull;

use App\Enums\Sorting;
use App\Http\Livewire\Traits\HasPagination;
use App\Http\Livewire\Traits\HasPreLoading;
use App\Http\Livewire\Wireables\FilterBag;
use App\Models\Origin;
use Illuminate\Support\Str;
use Livewire\Component;

class Index extends Component
{
    use HasPreLoading;
    use HasPagination;

    public int $perPage = 18;

    public FilterBag $filters;

    protected $listeners = [
        'toggleFilter',
    ];

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
        $this->dispatchBrowserEvent('update-browser-url', [
            'url' => route('pull.index', $queryString),
        ]);

        // Get the pulls
        $pulls = $this->filters->toPulls();

        return view('livewire.pull.index', [
            'pulls' => $pulls->limit($this->page * $this->perPage)->fetch(),
            'count' => $pulls->count(),
            'hasMore' => $pulls->count() > ($this->page * $this->perPage),
            'sortOptions' => Sorting::list(),
            'origins' => Origin::get()->mapWithKeys(function ($origin) {
                return [$origin->slug => view('components.origin', ['origin' => $origin])->render()];
            }),
        ]);
    }

    public function setFilterValues($sort, $origins, $mediaType)
    {
        $this->filters->setSorting($sort);
        $this->filters->setOrigins($origins);
        $this->filters->setMediaType($mediaType);

        $this->page = 1;
    }

    public function toggleFilter(string $type, $id)
    {
        $this->filters->toggleFilter($type, $id);

        $this->page = 1;
    }
}
