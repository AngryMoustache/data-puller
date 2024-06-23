<?php

namespace App\Livewire\Modal;

use App\Livewire\Traits\HasPagination;
use App\Livewire\Wireables\FilterBag;
use App\Models\Pull;
use App\Models\SavedTagGroup;
use App\Models\Tag;
use App\Models\TagGroup;
use Livewire\Attributes\On;

class GetExistingTagGroup extends Modal
{
    use HasPagination;

    public static string $view = 'livewire.modal.get-existing-tag-group-';

    public int $step = 1;

    // Step 1
    public int $perPage = 18;
    public FilterBag $filters;
    public null | Pull $pull = null;

    // Step 2 & 3
    public null | TagGroup $groupModel = null;
    public null | array $group = null;

    public function mount()
    {
        $this->filters = new FilterBag;
    }

    public function render()
    {
        return match ($this->step) {
            1 => $this->renderStep1(),
            2 => $this->renderStep2(),
            3 => $this->renderStep3(),
        };
    }

    public function renderStep1()
    {
        $pulls = $this->filters->toPulls();

        return view(self::$view . $this->step, [
            'pulls' => $pulls->limit($this->perPage)->fetch($this->filters),
        ]);
    }

    public function renderStep2()
    {
        return view(self::$view . $this->step, [
            'groups' => $this->pull->tagGroups()->orderBy('name')->get(),
        ]);
    }

    public function renderStep3()
    {
        return view(self::$view . $this->step, [
            'tags' => Tag::whereDoesntHave('parent')
                ->with('children.children.children.children.children')
                ->get(),
        ]);
    }

    #[On('toggleFilter')]
    public function toggleFilter(string $type, $id)
    {
        $this->filters->toggleFilter($type, $id);
    }

    public function selectPull(int $pullId)
    {
        $this->pull = Pull::find($pullId);
        $this->step = 2;
    }

    public function selectGroup(int $groupId)
    {
        $this->groupModel = TagGroup::find($groupId);

        $this->group['name'] = $this->groupModel->name;
        $this->group['tags'] = $this->groupModel->tags->pluck('id')
            ->mapWithKeys(fn ($id) => [$id => true])
            ->toArray();

        $this->step = 3;
    }

    public function confirm()
    {
        $group = SavedTagGroup::create([
            'name' => $this->group['name'],
            'attachment_id' => optional($this->pull->attachments->first()
                ?? $this->pull->first()?->preview)->id
        ]);

        $tags = Tag::checkOrphans(collect($this->group['tags'])->filter()->keys());

        $group->tags()->sync($tags);

        $this->dispatch('close-modal');
        $this->dispatch('refresh');

        $this->toast('New tag group has been created');
    }
}
