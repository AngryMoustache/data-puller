<?php

namespace App\Livewire\Sections;

use App\Pulls;
use App\Livewire\Traits\HasPreLoading;
use Livewire\Component;

#[\Livewire\Attributes\Isolate]
class HiddenGems extends Component
{
    use HasPreLoading;

    public function render()
    {
        if (! $this->loaded) {
            return $this->renderLoadingGrid(6);
        }

        return view('livewire.sections.recommendations', [
            'pulls' => Pulls::make()
                ->sortByDesc('views')
                ->limit(100)
                ->fetch()
                ->sortBy('updated_at')
                ->take(6),
        ]);
    }
}
