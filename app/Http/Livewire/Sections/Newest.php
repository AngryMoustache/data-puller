<?php

namespace App\Http\Livewire\Sections;

use App\Pulls;
use App\Http\Livewire\Traits\HasPreLoading;
use Livewire\Component;

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
