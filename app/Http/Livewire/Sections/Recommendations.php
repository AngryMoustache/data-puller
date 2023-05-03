<?php

namespace App\Http\Livewire\Sections;

use App\Pulls;
use App\Http\Livewire\Traits\HasPreLoading;
use App\Models\History;
use App\Models\Pull;
use Livewire\Component;

class Recommendations extends Component
{
    use HasPreLoading;

    public function render()
    {
        if (! $this->loaded) {
            return $this->renderLoadingGrid(6);
        }

        $newestPulls = Pulls::make()
            ->sortByDesc('verdict_at')
            ->limit(6)
            ->fetch();

        return view('livewire.sections.recommendations', [
            'pulls' => $newestPulls
                ->map(fn (Pull $pull) => $pull->related()->take(5)->random())
                ->shuffle(),
        ]);
    }
}
