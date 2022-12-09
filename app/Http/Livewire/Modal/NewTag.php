<?php

namespace App\Http\Livewire\Modal;

use App\Models\Tag;
use App\Models\TagGroup;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

class NewTag extends Component
{
    public string $name = '';
    public int $group;

    public Collection $groups;

    public function mount($params)
    {
        $this->group = $params[0];
        $this->groups = TagGroup::pluck('name', 'id');
    }

    public function save()
    {
        if (empty($this->name)) {
            return;
        }

        $tag = new Tag;
        $tag->name = $this->name;
        $tag->slug = Str::slug($this->name);
        $tag->tag_group_id = $this->group;
        $tag->save();

        $this->emit('closeModal');
        $this->emit('refreshComponent');
    }
}
