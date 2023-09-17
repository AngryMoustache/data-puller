<?php

namespace App\Livewire\Sections;

use App\Pulls;
use App\Livewire\Traits\HasPreLoading;
use App\Models\History;
use Livewire\Component;

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
                ->sortBy('views')
                ->limit(24)
                ->fetch()
                ->shuffle()
                ->take(6),
        ]);
    }
}
