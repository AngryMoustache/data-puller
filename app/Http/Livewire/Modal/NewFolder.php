<?php

namespace App\Http\Livewire\Modal;

use App\Models\Folder;
use Illuminate\Support\Str;
use Livewire\Component;

class NewFolder extends Component
{
    public string $name = '';
    public string $description = '';

    public function save()
    {
        if (empty($this->name)) {
            return;
        }

        $group = new Folder;
        $group->name = $this->name;
        $group->slug = Str::slug($this->name);
        $group->description = $this->description;
        $group->save();

        $this->emit('closeModal');
    }
}
