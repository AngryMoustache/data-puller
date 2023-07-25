<?php

namespace App\Http\Livewire\History;

use App\Http\Livewire\Traits\HasPreLoading;
use App\Models\History;
use Livewire\Component;

class Index extends Component
{
    use HasPreLoading;

    public string $current;

    public function render()
    {
        app('site')->title('History');

        if (! $this->loaded) {
            return $this->renderLoadingListContainer();
        }

        $history = History::with('pull.tags')
            ->orderBy('last_viewed_at', 'desc')
            ->get()
            ->groupBy('viewed_on')
            ->mapWithKeys(fn ($group) => [
                $group->first()->viewed_on->format('l, F jS') => $group->pluck('pull')
            ]);

        $this->current ??= $history->keys()->first();

        return view('livewire.history.index', [
            'history' => $history[$this->current],
            'days' => $history->keys(),
        ]);
    }

    public function changeDay(string $day)
    {
        $this->current = $day;
    }
}
