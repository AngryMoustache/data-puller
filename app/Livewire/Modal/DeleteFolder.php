<?php

namespace App\Livewire\Modal;

use Api\Jobs\RebuildCache;
use App\Livewire\Traits\CanToast;
use Livewire\Component;

class DeleteFolder extends Component
{
    use CanToast;

    public string $folderClass;
    public int $folderId;
    public string $name;

    public function mount(array $params = [])
    {
        $this->folderClass = $params['class'] ?? null;
        $this->folderId = $params['id'] ?? null;
        $this->name = $params['name'] ?? null;
    }

    public function delete()
    {
        $this->folderClass::where('id', $this->folderId)->delete();

        RebuildCache::dispatch();
        $this->dispatch('close-modal');
        $this->dispatch('refresh');

        $this->toast('Folder has been deleted');
    }
}
