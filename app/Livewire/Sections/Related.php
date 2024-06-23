<?php

namespace App\Livewire\Sections;

use App\Livewire\Traits\HasPagination;
use App\Livewire\Traits\HasPreLoading;
use App\Models\Pull;
use App\Site;
use Livewire\Component;

#[\Livewire\Attributes\Isolate]
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
        // $slideshow = Site::slideshow(
        //     Pull::find($this->pullId)->related(50)->pluck('id')
        // );

        return view('livewire.sections.related', [
            'pulls' => $pulls,
            // 'slideshow' => $slideshow,
        ]);
    }
}
