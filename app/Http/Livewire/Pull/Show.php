<?php

namespace App\Http\Livewire\Pull;

use Api\Jobs\RebuildCache;
use App\Models\Folder;
use App\Models\History;
use App\Models\Pull;
use Illuminate\Support\Collection;
use Livewire\Component;

class Show extends Component
{
    public Pull $pull;

    public Collection $folders;

    public $listeners = [
        'refresh' => '$refresh',
    ];

    public function mount(Pull $pull)
    {
        if (! $pull->online) {
            abort(404);
        }

        $this->pull = $pull;
        $this->folders = Folder::orderBy('name')
            ->whereHas('pulls')
            ->get();

        History::add($pull);
    }

    public function toggleFromFolder(int $folderId)
    {
        $this->pull->folders()->toggle($folderId);
        $this->emitSelf('refresh');

        RebuildCache::dispatch();
    }
}
