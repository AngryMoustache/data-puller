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

    public int $perPage = 6;

    public function render()
    {
        if (! $this->loaded) {
            return $this->renderLoadingListContainer();
        }

        $pulls = Pull::pending()->latest()->get();

        $archived = Pull::offline()
            ->orderBy('verdict_at', 'desc')
            ->where(fn ($query) => $query->whereHas('attachments')->orWhereHas('videos'))
            ->latest()
            ->limit($this->page * $this->perPage)
            ->get();

        return view('livewire.feed.index', [
            'pulls' => $pulls,
            'archived' => $archived,
            'hasMore' => Pull::offline()->count() > $archived->count(),
        ]);
    }
}
