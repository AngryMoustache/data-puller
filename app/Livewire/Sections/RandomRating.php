<?php

namespace App\Livewire\Sections;

use Api\Jobs\RebuildCache;
use App\Models\Pull;
use App\Models\RatingCategory;
use Illuminate\Support\Collection;
use Livewire\Component;

class RandomRating extends Component
{
    public Pull $pull;

    public Collection $categories;

    public array $ratings = [];

    public function mount()
    {
        $this->nextRating();
    }

    public function render()
    {
        return view('livewire.sections.random-rating', [
            'count' => Pull::online()
                ->withCount('ratings')
                ->having('ratings_count', '!=', RatingCategory::count())
                ->count() - 1,
        ]);
    }

    public function nextRating()
    {
        $this->pull = Pull::online()
            ->withCount('ratings')
            ->having('ratings_count', '!=', RatingCategory::count())
            ->inRandomOrder()
            ->first() ?? Pull::online()->inRandomOrder()->first();

        $this->categories = RatingCategory::get();

        // Create the rating at 5 if it doesn't exist
        $missing = RatingCategory::query()
            ->whereNotIn('id', $this->pull->ratings->pluck('pivot.rating_category_id'))
            ->get()
            ->mapWithKeys(fn (RatingCategory $category) => [$category->id => 5]);

        $this->ratings = $this->pull->ratings
            ->pluck('pivot.rating', 'pivot.rating_category_id')
            ->union($missing)
            ->toArray();
    }

    public function save()
    {
        $ratings = collect($this->ratings)
            ->mapWithKeys(fn (string | int $rating, int $category) => [
                $category => ['rating' => (int) $rating],
            ])
            ->toArray();

        $this->pull->timestamps = false;
        $this->pull->ratings()->sync($ratings);

        $this->nextRating();

        $this->dispatch('saved-rating');

        RebuildCache::dispatch();
    }
}
