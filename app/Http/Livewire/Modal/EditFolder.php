<?php

namespace App\Http\Livewire\Modal;

use Api\Jobs\RebuildCache;
use App\Http\Livewire\Traits\CanToast;
use Livewire\Component;

class EditFolder extends Component
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

    public function render()
    {
        return view('livewire.modal.new-folder');
    }

    public function save()
    {
        if (empty($this->name)) {
            return;
        }

        $this->folderClass::where('id', $this->folderId)
            ->update(['name' => $this->name]);

        RebuildCache::dispatch();
        $this->dispatchBrowserEvent('close-modal');
        $this->emit('refresh');

        $this->toast('Folder has been updated');
    }
}
