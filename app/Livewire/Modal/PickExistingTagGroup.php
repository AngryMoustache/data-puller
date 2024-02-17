<?php

namespace App\Livewire\Modal;

use App\Models\SavedTagGroup;
use App\Models\Tag;
use App\Models\TagGroup;

class PickExistingTagGroup extends Modal
{
    public int $step = 1;

    public int $pull;
    public null | SavedTagGroup $groupModel = null;
    public array $group = [];

    public function mount(array $params = [])
    {
        $this->pull = $params['pull_id'];
    }

    public function render()
    {
        if ($this->step === 1) {
            return view('livewire.modal.pick-existing-tag-group-1', [
                'groups' => SavedTagGroup::orderBy('name')->with('tags')->get(),
            ]);
        }

        return view('livewire.modal.pick-existing-tag-group-2', [
            'groups' => SavedTagGroup::orderBy('name')->with('tags')->get(),
        ]);
    }

    public function select(int $id)
    {
        $this->groupModel = SavedTagGroup::find($id);

        $this->group['name'] = $this->groupModel->name;
        $this->group['tags'] = $this->groupModel->tags->pluck('id')
            ->mapWithKeys(fn ($id) => [$id => true])
            ->toArray();

        $this->step = 2;
    }

    public function confirm()
    {
        $group = TagGroup::create([
            'pull_id' => $this->pull,
            'name' => $this->group['name'],
            'is_main' => false,
        ]);

        $tags = Tag::checkOrphans(collect($this->group['tags'])->filter()->keys());
        $group->tags()->sync($tags);

        $this->dispatch('closeModal');
        $this->dispatch('updated-tag-group', [
            'groupKey' => $group->id,
            'group' => $group->toJavascript(),
        ]);
    }
}
