<?php

namespace App\Livewire\Modal;

use App\Models\SavedTagGroup;
use App\Models\Tag;

class UpdateTagGroup extends Modal
{
    public array $group;

    public function mount(array $params = [])
    {
        $group = SavedTagGroup::find($params['id'] ?? null);

        if ($group) {
            $this->group = [
                'id' => $group->id,
                'name' => $group->name,
                'tags' => $group->tags->pluck('id')->mapWithKeys(fn (int $id) => [$id => true])->toArray(),
            ];
        }
    }

    public function render()
    {
        return view('livewire.modal.update-tag-group', [
            'tags' => Tag::whereDoesntHave('parent')
                ->with('children.children.children.children.children')
                ->get(),
        ]);
    }

    public function save()
    {
        $group = ($this->group['id'] ?? false)
            ? SavedTagGroup::find($this->group['id'])
            : new SavedTagGroup;

        $group->name = $this->group['name'];
        $group->save();

        $tags = Tag::checkOrphans(collect($this->group['tags'] ?? [])->filter()->keys());
        $group->tags()->sync($tags->toArray());

        $this->toast('Tag group created/updated');

        $this->dispatch('closeModal');
        $this->dispatch('refresh');
    }
}
