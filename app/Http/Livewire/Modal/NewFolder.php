<?php

namespace App\Http\Livewire\Modal;

use App\Http\Livewire\Pull\Show;
use App\Models\DynamicFolder;
use App\Models\Folder;
use Livewire\Component;

class NewFolder extends Component
{
    public string $name = '';

    public null | int $pullId;

    public null | string $filters;

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

        $this->dispatchBrowserEvent('close-modal');
        $this->emitTo(Show::class, 'refresh');
    }

    public function saveDynamicFolder()
    {
        $folder = new DynamicFolder;
        $folder->name = $this->name;
        $folder->filter_string = $this->filters;
        $folder->save();
    }
}
