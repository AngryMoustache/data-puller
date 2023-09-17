<?php

namespace App\Livewire\Modal;

use App\Livewire\Traits\CanToast;
use App\Models\Tag;
use Livewire\Component;

class EditTag extends Component
{
    use CanToast;

    public int $tagId;
    public string $name;
    public string | int $parent = '';

    public function mount(array $params = [])
    {
        $this->tagId = $params['id'] ?? null;
        $this->name = $params['name'] ?? null;
        $this->parent = $params['parent'] ?? '';
    }

    public function render()
    {
        $tags = Tag::orderBy('long_name')
            ->where('id', '!=', $this->tagId)
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

        $tag = Tag::find($this->tagId);
        $tag->name = $this->name;
        $tag->parent_id = filled($this->parent) ? $this->parent : null;
        $tag->save();

        $this->dispatch('close-modal');
        $this->dispatch(Show::class, 'refresh');

        $this->toast('Tag has been updated');
    }
}
