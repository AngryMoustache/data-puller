<?php

namespace App\Livewire\Modal;

use App\Models\Tag;

class TagGroupSelector extends Modal
{
    public int $groupKey;
    public array $group;
    public array $media;

    public $listeners = [
        'refresh' => '$refresh',
    ];

    public function mount(array $params = [])
    {
        $this->groupKey = $params['groupKey'];
        $this->group = $params['group'];
        $this->media = $params['media'];
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
        $this->toast('Tag group updated');

        $this->dispatch('closeModal');

        $this->dispatch('updated-tag-group', [
            'groupKey' => $this->groupKey,
            'group' => $this->group,
        ]);
    }
}
