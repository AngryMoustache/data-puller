<?php

namespace App\Livewire\Modal;

use App\Livewire\Feed\Show;
use App\Livewire\Traits\CanToast;
use App\Models\Tag;
use Livewire\Component;

class NewTag extends Component
{
    use CanToast;

    public string $name = '';

    public string | int $parent = '';

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
        $tag->parent_id = filled($this->parent) ? $this->parent : null;
        $tag->save();

        $this->dispatch('close-modal');
        $this->dispatch(Show::class, 'refresh');

        $this->toast('Tag has been created');
    }
}
