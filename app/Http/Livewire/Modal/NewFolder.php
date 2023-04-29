<?php

namespace App\Http\Livewire\Modal;

use App\Http\Livewire\Pull\Show;
use App\Models\Folder;
use Livewire\Component;

class NewFolder extends Component
{
    public string $name = '';

    public int $pullId;

    public function mount(array $params = [])
    {
        $this->pullId = $params['pullId'];
    }

    public function save()
    {
        if (empty($this->name)) {
            return;
        }

        $folder = new Folder;
        $folder->name = $this->name;
        $folder->save();

        $folder->pulls()->attach($this->pullId);

        $this->dispatchBrowserEvent('close-modal');
        $this->emitTo(Show::class, 'refresh');
    }
}
