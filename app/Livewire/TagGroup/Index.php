<?php

namespace App\Livewire\TagGroup;

use App\Livewire\Traits\CanToast;
use App\Models\SavedTagGroup;
use Livewire\Component;

class Index extends Component
{
    use CanToast;

    public string $current;

    public $listeners = [
        'refresh' => '$refresh',
    ];

    public function render()
    {
        app('site')->title('Tag Groups');

        return view('livewire.tag-group.index', [
            'groups' => SavedTagGroup::orderBy('name')->get(),
        ]);
    }

    public function deleteGroup($id)
    {
        $group = SavedTagGroup::find($id);
        $group->delete();

        $this->toast('Tag group deleted');
    }
}
