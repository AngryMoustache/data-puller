<?php

namespace App\Http\Livewire\Sections;

use App\Http\Livewire\Traits\HasPreLoading;
use App\Models\Pull;
use App\Pulls;
use Livewire\Component;

class Related extends Component
{
    use HasPreLoading;

    public int $pullId;

    public function mount(Pull $pull)
    {
        $this->pullId = $pull->id;
    }

    public function render()
    {
        if (! $this->loaded) {
            return $this->renderLoadingList();
        }

        $tags = Pull::find($this->pullId)->tags->pluck('id')->toArray();

        $pulls = Pulls::make()
            ->where('id', '!=', $this->pullId)
            ->sortByDesc(function (array $pull) use ($tags) {
                return collect($pull['tags'])->pluck('id')->intersect($tags)->count();
            })
            ->take(10);

        return view('livewire.sections.related', [
            'pulls' => $pulls->fetch(),
        ]);
    }
}
