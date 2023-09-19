<?php

namespace App\Livewire\Sections;

use App\Pulls;
use App\Livewire\Traits\HasPreLoading;
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
            ->limit(12)
            ->fetch();

        $related = $newestPulls
            ->map(fn (Pull $pull) => $pull->related()->take(5)->random())
            ->unique()
            ->flatten();

        return view('livewire.sections.recommendations', [
            'pulls' => $related->take(6)->shuffle(),
        ]);
    }
}
