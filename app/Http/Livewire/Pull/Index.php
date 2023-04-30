<?php

namespace App\Http\Livewire\Pull;

use App\Enums\FilterTypes;
use App\Enums\Sorting;
use App\Http\Livewire\Traits\HasPagination;
use App\Http\Livewire\Traits\HasPreLoading;
use App\Http\Livewire\Wireables\FilterBag;
use App\Models\Origin;
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
    }

    public function render()
    {
        if (! $this->loaded) {
            return $this->renderLoadingGridContainer(18);
        }

        // Update the URL
        $this->dispatchBrowserEvent('update-browser-url', [
            'url' => route('pull.index', $this->filters->buildQueryString()),
        ]);

        // Get the pulls
        $pulls = $this->filters->toPulls();

        return view('livewire.pull.index', [
            'pulls' => $pulls->take($this->page * $this->perPage)->fetch(),
            'count' => $pulls->count(),
            'hasMore' => $pulls->count() > ($this->page * $this->perPage),
            'sortOptions' => Sorting::list(),
            'origins' => Origin::get()->mapWithKeys(function ($origin) {
                return [$origin->slug => view('components.origin', ['origin' => $origin])->render()];
            }),
        ]);
    }

    public function setFilterValues($sort, $origin)
    {
        $this->filters->setSorting($sort);
        $this->filters->setOrigin($origin);
    }

    public function toggleFilter(string $type, null | int $id)
    {
        $this->filters->toggleFilter($type, $id);
    }

    // public function toggleFolder(int $id)
    // {
    //     // $folder = Folder::find($id);

    //     // if ($this->selectedFolders->contains($folder)) {
    //     //     $this->selectedFolders = $this->selectedFolders->reject(fn ($item) => $item->id === $id);
    //     // } else {
    //     //     $this->selectedFolders->push($folder);
    //     // }
    // }
}
