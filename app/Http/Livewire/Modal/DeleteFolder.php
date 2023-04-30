<?php

namespace App\Http\Livewire\Modal;

use Api\Jobs\RebuildCache;
use Livewire\Component;

class DeleteFolder extends Component
{
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
        $this->dispatchBrowserEvent('close-modal');
        $this->emit('refresh');
    }
}
