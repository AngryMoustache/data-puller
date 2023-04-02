<?php

namespace App\Http\Livewire\Modal;

use App\Models\Tag;
use Livewire\Component;

class NewTag extends Component
{
    public string $name = '';

    public null|int $parent = null;

    public function mount(array $params = [])
    {
        $this->parent = $params['parent'] ?? null;
    }

    public function render()
    {
        $tags = Tag::orderBy('long_name')
            ->get()
            ->mapWithKeys(fn ($tag) => [$tag->id => "{$tag->long_name} ({$tag->name})"])
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

        $tag = new Tag;
        $tag->name = $this->name;
        $tag->parent_id = $this->parent;
        $tag->save();

        $this->emit('closeModal');
    }
}
