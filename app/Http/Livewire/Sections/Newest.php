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
            return $this->renderLoadingGrid();
        }

        $pulls = Pulls::make()
            ->sortByDesc('verdict_at')
            ->take(5);

        return view('livewire.sections.newest', [
            'pulls' => $pulls->fetch(),
        ]);
    }
}
