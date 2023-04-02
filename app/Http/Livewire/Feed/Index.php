<?php

namespace App\Http\Livewire\Feed;

use App\Http\Livewire\Traits\HasPagination;
use App\Http\Livewire\Traits\HasPreLoading;
use App\Models\Pull;
use Livewire\Component;

class Index extends Component
{
    use HasPreLoading;
    use HasPagination;

    public int $perPage = 5;

    public function render()
    {
        if (! $this->loaded) {
            return $this->renderLoadingListContainer();
        }

        $pulls = Pull::pending()->latest()->limit($this->page * $this->perPage)->get();
        $archived = Pull::offline()->latest()->limit($this->page * $this->perPage)->get();

        $hasMorePulls = Pull::pending()->count() > $pulls->count();
        $hasMoreArchived = Pull::offline()->count() > $archived->count();

        return view('livewire.feed.index', [
            'pulls' => $pulls,
            'archived' => $archived,
            'hasMorePulls' => $hasMorePulls,
            'hasMoreArchived' => $hasMoreArchived,
        ]);
    }
}
