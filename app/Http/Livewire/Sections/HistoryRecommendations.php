<?php

namespace App\Http\Livewire\Sections;

use App\Pulls;
use App\Http\Livewire\Traits\HasPreLoading;
use App\Models\History;
use Livewire\Component;

class HistoryRecommendations extends Component
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
            ->pluck('tags')
            ->flatten()
            ->countBy();

        $pulls = Pulls::make()
            ->sortByDesc(fn (array $pull) => collect($pull['tags'])->sum(fn ($tag) => $tags[$tag] ?? 0))
            ->whereNotIn('id', $history)
            ->limit(18)
            ->fetch()
            ->shuffle();

        return view('livewire.sections.recommendations', [
            'pulls' => $pulls,
        ]);
    }
}
