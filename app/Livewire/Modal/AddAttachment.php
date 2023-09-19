<?php

namespace App\Livewire\Modal;

use AngryMoustache\Media\Models\Attachment;
use App\Models\Pull;
use App\PullMedia;
use Livewire\Component;
use Livewire\WithPagination;

class AddAttachment extends Component
{
    use WithPagination;

    public null | int $pullId = null;

    public array $selected = [];

    public bool $forceLoading = false;

    public function mount(array $params = [])
    {
        $this->selected = $params['selected'] ?? [];
    }

    public function render()
    {
        $pulls = Pull::whereHas('attachments')
            ->orWhereHas('videos')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.modal.add-attachment', [
            'pulls' => $pulls,
            'attachments' => $this->pullId
                ? Pull::find($this->pullId)?->media
                : Attachment::latest()->take(18)->get()->mapInto(PullMedia::class),
        ]);
    }

    public function selectPull(int $id)
    {
        $this->pullId = $id;
    }

    public function addSelected(array $selected)
    {
        // We close the modal in the target
        $this->dispatch('set-media', $selected);
        $this->forceLoading = true;
    }
}
