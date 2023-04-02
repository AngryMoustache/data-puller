<?php

namespace App\Http\Livewire\Sections;

use App\Pulls;
use App\Http\Livewire\Traits\HasPreLoading;
use App\Models\History;
use Livewire\Component;

class Recommendations extends Component
{
    use HasPreLoading;

    public function render()
    {
        if (! $this->loaded) {
            return $this->renderLoadingGrid(18);
        }

        $history = History::limit(5)
            ->orderBy('last_viewed_at', 'desc')
            ->pluck('pull_id');

        $tags = Pulls::make()
            ->whereIn('id', $history)
            ->pluck('tags.*.id')
            ->flatten()
            ->countBy();

        $pulls = Pulls::make()
            ->sortByDesc(function (array $pull) use ($tags) {
                return collect($pull['tags'])
                    ->pluck('id')
                    ->sum(fn ($id) => $tags[$id] ?? 0);
            })
            ->whereNotIn('id', $history)
            ->take(18);

        return view('livewire.sections.recommendations', [
            'pulls' => $pulls->fetch()->shuffle(),
        ]);
    }
}
