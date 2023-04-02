<?php

namespace App\Http\Livewire\History;

use App\Http\Livewire\Traits\HasPagination;
use App\Http\Livewire\Traits\HasPreLoading;
use App\Models\History;
use Livewire\Component;

class Index extends Component
{
    use HasPreLoading;
    use HasPagination;

    public int $perPage = 10;

    public function render()
    {
        if (! $this->loaded) {
            return $this->renderLoadingListContainer();
        }

        $items = History::with('pull.tags')
            ->orderBy('last_viewed_at', 'desc')
            ->get();

        $history = $items
            ->take($this->page * $this->perPage + 1)
            ->groupBy('viewed_on')
            ->mapWithKeys(fn ($group) => [
                $group->first()->viewed_on->format('l, F jS') => $group->pluck('pull')
            ]);

        return view('livewire.history.index', [
            'history' => $history,
            'hasMore' => $history->flatten()->count() > ($this->page * $this->perPage),
        ]);
    }
}
