<?php

namespace App\Livewire\Sections;

use App\Pulls;
use App\Livewire\Traits\HasPreLoading;
use Livewire\Component;

#[\Livewire\Attributes\Isolate]
class Newest extends Component
{
    use HasPreLoading;

    public function render()
    {
        if (! $this->loaded) {
            return $this->renderLoadingGrid(6);
        }

        return view('livewire.sections.newest', [
            'pulls' => Pulls::make()
                ->sortByDesc('verdict_at')
                ->limit(6)
                ->fetch(),
        ]);
    }
}
