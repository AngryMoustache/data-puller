<?php

namespace App\Livewire\Modal;

use App\Models\Tag;
use App\Models\TagGroup;
use Illuminate\Support\Collection;

class AddExistingTagGroup extends Modal
{
    public int $pullId;

    public null | int $groupId = null;
    public null | TagGroup $groupModel = null;

    public Collection $groups;

    public array $group = [
        'tags' => [],
    ];

    public function mount(array $params = [])
    {
        $this->pullId = $params['pull_id'];

        $this->groups = TagGroup::whereNull('pull_id')
            ->orderBy('name')
            ->get();
    }

    public function updatedGroupId(null | int $value)
    {
        $this->groupModel = TagGroup::find($value);

        if ($this->groupModel) {
            $this->group['tags'] = $this->groupModel->tags->pluck('id')
                ->mapWithKeys(fn ($id) => [$id => true])
                ->toArray();
        } else {
            $this->group['tags'] = [];
        }
    }

    public function deleteGroup()
    {
        $this->groupModel->delete();

        $this->group['tags'] = [];
        $this->groupModel = null;
        $this->groupId = null;

        $this->groups = TagGroup::whereNull('pull_id')
            ->orderBy('name')
            ->get();

        $this->toast('Tag group has been deleted');
    }

    public function confirm()
    {
        $group = TagGroup::create([
            'name' => $this->groupModel->name,
            'pull_id' => $this->pullId,
            'is_main' => false,
        ]);

        $tags = Tag::checkOrphans(collect($this->group['tags'])->filter()->keys());

        $group->tags()->sync($tags);

        $this->dispatch('close-modal');
        $this->dispatch('refresh');

        $this->dispatch('added-existing-group', [
            ...$group->toArray(),
            'tags' => $group->tags->pluck('id')
                ->mapWithKeys(fn ($t) => [$t => true])
                ->toArray(),
        ]);

        $this->toast('Tag group has been added');
    }
}
