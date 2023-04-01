<?php

namespace App\Http\Livewire\Sections;

use App\Http\Livewire\Traits\HasPagination;
use App\Http\Livewire\Traits\HasPreLoading;
use App\Models\Pull;
use App\Pulls;
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

        $tags = Pull::find($this->pullId)->tags->pluck('id')->toArray();

        $pulls = Pulls::make()
            ->where('id', '!=', $this->pullId)
            ->sortByDesc(fn (array $pull) => collect($pull['tags'])->pluck('id')->intersect($tags)->count())
            ->take($this->page * $this->perPage);

        return view('livewire.sections.related', [
            'pulls' => $pulls->fetch(),
        ]);
    }
}
