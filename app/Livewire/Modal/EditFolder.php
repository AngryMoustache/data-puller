<?php

namespace App\Livewire\Modal;

use Api\Jobs\RebuildCache;

class EditFolder extends Modal
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
        $this->dispatch('close-modal');
        $this->dispatch('refresh');

        $this->toast('Folder has been updated');
    }
}
