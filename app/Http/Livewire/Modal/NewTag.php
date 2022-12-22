<?php

namespace App\Http\Livewire\Modal;

use App\Models\Tag;
use Livewire\Component;

class NewTag extends Component
{
    public string $name = '';
    public ?int $parent = null;

    public function render()
    {
        $tags = Tag::orderBy('long_name')
            ->pluck('long_name', 'id')
            ->toArray();

        return view('livewire.modal.new-tag', [
            'tags' => $tags,
        ]);
    }

    public function save()
    {
        if (empty($this->name)) {
            return;
        }

        $group = new Tag;
        $group->name = $this->name;
        $group->save();

        $this->emit('closeModal');
    }
}
