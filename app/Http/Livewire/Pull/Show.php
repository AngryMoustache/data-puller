<?php

namespace App\Http\Livewire\Pull;

use Api\Jobs\RebuildCache;
use App\Enums\Status;
use App\Http\Livewire\Traits\CanToast;
use App\Models\Folder;
use App\Models\History;
use App\Models\Pull;
use Illuminate\Support\Collection;
use Livewire\Component;

class Show extends Component
{
    use CanToast;

    public Pull $pull;

    public Collection $folders;

    public $listeners = [
        'refresh' => '$refresh',
    ];

    public function mount(Pull $pull)
    {
        if ($pull->status !== Status::ONLINE) {
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

        $this->toast('Folder successfully updated');
    }
}
