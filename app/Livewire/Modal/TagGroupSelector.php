<?php

namespace App\Livewire\Modal;

use App\Livewire\Traits\CanToast;
use App\Models\Tag;
use Livewire\Component;

class TagGroupSelector extends Component
{
    use CanToast;

    public int $groupKey;
    public string $groupName;
    public array $selectedTags;
    public bool $isMain;
    public array $uniqueNames;
    public array $media;

    public function mount(array $params = [])
    {
        $this->groupKey = (int) ($params['groupKey'] ?? 0);
        $this->groupName = $params['group']['name'] ?? 'Unknown group';
        $this->selectedTags = $params['group']['tags'] ?? [];
        $this->isMain = (int) $params['group']['is_main'] ?? 0;
        $this->uniqueNames = $params['uniqueNames'] ?? [];
        $this->media = $params['media'] ?? [];
    }

    public function render()
    {
        return view('livewire.modal.tag-group-selector', [
            'tags' => Tag::whereDoesntHave('parent')
                ->with('children.children.children.children.children')
                ->get(),
        ]);
    }

    public function save()
    {
        if (in_array($this->groupName, $this->uniqueNames)) {
            $this->toast('Group name must be unique!');

            return;
        }

        $this->dispatch('updated-tag-group', [
            'groupKey' => $this->groupKey,
            'group' => [
                'name' => $this->groupName,
                'is_main' => $this->isMain,
                'tags' => $this->selectedTags,
            ],
        ]);

        $this->toast('Tag group updated');

        $this->dispatch('closeModal');
    }
}
