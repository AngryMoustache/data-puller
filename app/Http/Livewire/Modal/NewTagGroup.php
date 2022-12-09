<?php

namespace App\Http\Livewire\Modal;

use App\Models\TagGroup;
use Illuminate\Support\Str;
use Livewire\Component;

class NewTagGroup extends Component
{
    public string $name = '';
    public string $description = '';

    public function save()
    {
        if (empty($this->name)) {
            return;
        }

        $group = new TagGroup;
        $group->name = $this->name;
        $group->slug = Str::slug($this->name);
        $group->description = $this->description;
        $group->save();

        $this->emit('closeModal');
    }
}
