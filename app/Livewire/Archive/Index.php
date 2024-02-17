<?php

namespace App\Livewire\Archive;

use App\Livewire\Traits\HasPagination;
use App\Livewire\Traits\HasPreLoading;
use App\Models\Pull;
use Livewire\Component;

class Index extends Component
{
    use HasPreLoading;
    use HasPagination;

    public int $perPage = 24;

    public string $query = '';
    public string $sort = 'created_at--desc';

    public function render()
    {
        app('site')->title('Archive');

        if (! $this->loaded) {
            return $this->renderLoadingGridContainer();
        }

        $pulls = Pull::offline()
            ->with('attachments', 'videos', 'artist')
            ->where(fn ($query) => $query->whereHas('attachments')->orWhereHas('videos'))
            ->when($this->query, fn ($query) => $query->simpleSearch($this->query))
            ->orderBy(...explode('--', $this->sort))
            ->limit($this->page * $this->perPage)
            ->get();

        return view('livewire.archive.index', [
            'pulls' => $pulls,
            'hasMore' => Pull::offline()->count() > $pulls->count(),
        ]);
    }
}
