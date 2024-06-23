<?php

namespace App\Livewire\Sections;

use Api\Jobs\RebuildCache;
use App\Models\Pull;
use App\Models\RatingCategory;
use Illuminate\Support\Collection;
use Livewire\Component;

#[\Livewire\Attributes\Isolate]
class Rating extends Component
{
    public Pull $pull;

    public Collection $categories;

    public array $ratings = [];

    public function mount(Pull $pull)
    {
        $this->categories = RatingCategory::get();
        $this->ratings = $pull->ratings
            ->pluck('pivot.rating', 'pivot.rating_category_id')
            ->toArray();
    }

    public function updatedRatings()
    {
        $ratings = collect($this->ratings)
            ->filter()
            ->mapWithKeys(fn (null | string | int $rating, int $category) => [
                $category => ['rating' => (int) $rating],
            ])
            ->toArray();

        $this->pull->timestamps = false;
        $this->pull->ratings()->sync($ratings);

        RebuildCache::dispatch();
    }
}
