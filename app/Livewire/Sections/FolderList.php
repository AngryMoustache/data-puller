<?php

namespace App\Livewire\Sections;

use Api\Jobs\RebuildCache;
use App\Livewire\Traits\CanToast;
use App\Models\Folder;
use App\Models\Pull;
use Livewire\Component;

class FolderList extends Component
{
    use CanToast;

    public Pull $pull;

    public $listeners = [
        'refresh' => '$refresh',
    ];

    public function render()
    {
        return view('livewire.sections.folder-list', [
            'folders' => Folder::get(),
        ]);
    }

    public function toggleFromFolder(int $folderId)
    {
        $this->pull->folders()->toggle($folderId);
        $this->dispatch('refresh');

        RebuildCache::dispatch();

        $folder = Folder::find($folderId);

        if ($folder->pulls->isEmpty()) {
            $folder->delete();
            $this->toast('Folder successfully deleted');
        } else {
            $this->toast('Folder successfully updated');
        }
    }
}
