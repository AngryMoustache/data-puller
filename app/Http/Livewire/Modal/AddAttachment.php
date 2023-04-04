<?php

namespace App\Http\Livewire\Modal;

use App\Models\Pull;
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
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $this->pullId ??= $pulls->first()?->id;

        return view('livewire.modal.add-attachment', [
            'attachments' => Pull::find($this->pullId)?->attachments,
            'pulls' => $pulls,
        ]);
    }

    public function selectPull(int $id)
    {
        $this->pullId = $id;
    }

    public function addSelected(array $selected)
    {
        // We close the modal in the target
        $this->emit('add-attachments', $selected);
        $this->forceLoading = true;
    }
}
