<?php

namespace App\Http\Livewire\Sections;

use App\Http\Livewire\Traits\HasPagination;
use App\Http\Livewire\Traits\HasPreLoading;
use App\Models\Pull;
use Livewire\Component;

class Related extends Component
{
    use HasPreLoading;
    use HasPagination;

    public int $perPage = 10;

    public int $pullId;

    public function mount(Pull $pull)
    {
        $this->pullId = $pull->id;
    }

    public function render()
    {
        if (! $this->loaded) {
            return $this->renderLoadingList($this->perPage);
        }

        $pulls = Pull::find($this->pullId)->related($this->page * $this->perPage);

        return view('livewire.sections.related', [
            'pulls' => $pulls,
        ]);
    }
}
