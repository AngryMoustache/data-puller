<?php

namespace App\Livewire\Modal;

use Api\Jobs\RebuildCache;
use App\Livewire\Sections\FolderList;
use App\Models\DynamicFolder;
use App\Models\Folder;

class NewFolder extends Modal
{
    public string $name = '';

    public null | int $pullId = null;

    public null | string $filters = null;

    public function mount(array $params = [])
    {
        $this->pullId = $params['pullId'] ?? null;
        $this->filters = $params['filters'] ?? null;
    }

    public function save()
    {
        if (empty($this->name)) {
            return;
        }

        if (! is_null($this->filters)) {
            $this->saveDynamicFolder();
        } else {
            $folder = new Folder;
            $folder->name = $this->name;
            $folder->save();

            $folder->pulls()->attach($this->pullId);
        }

        RebuildCache::dispatch();

        $this->dispatch('close-modal');
        $this->dispatch(FolderList::class, 'refresh');

        $this->toast('Folder has been created');
    }

    public function saveDynamicFolder()
    {
        $folder = new DynamicFolder;
        $folder->name = $this->name;
        $folder->filter_string = $this->filters;
        $folder->save();
    }
}
