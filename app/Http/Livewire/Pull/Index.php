<?php

namespace App\Http\Livewire\Pull;

use App\Http\Livewire\Traits\HasPagination;
use App\Http\Livewire\Traits\HasPreLoading;
use App\Pulls;
use Livewire\Component;

class Index extends Component
{
    use HasPreLoading;
    use HasPagination;

    public int $perPage = 20;

    public array $filters = [];

    public function mount(string $filterString = '')
    {
        $this->filters = collect(explode('/', $filterString))->mapWithKeys(function ($value, $key) {
            [$key, $value] = explode(':', $value);

            return [$key => explode(',', $value)];
        })->toArray();
    }

    public function render()
    {
        if (! $this->loaded) {
            return $this->renderLoadingGridContainer(15);
        }

        $pulls = Pulls::make()
            ->filter(function ($pull) {
                return collect($pull['tags'])->pluck('slug')->intersect($this->filters['tags'] ?? [])->count() === count($this->filters['tags'] ?? []);
            })
            ->sortByDesc('verdict_at')
            ->take($this->page * $this->perPage)
            ->fetch();

        return view('livewire.pull.index', [
            'pulls' => $pulls,
        ]);
    }
}
