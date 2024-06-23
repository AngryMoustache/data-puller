<?php

namespace App\Livewire\Settings;

use Api\Jobs\RebuildCache;
use App\Livewire\Traits\CanToast;
use App\Models\RatingCategory;
use Illuminate\Support\Collection;
use Livewire\Component;

class RatingCategoriesSettings extends Component
{
    use CanToast;

    public array $categories;

    public function mount()
    {
        $this->categories = RatingCategory::get()->map(function (RatingCategory $category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'icon' => $category->icon,
                'slug' => $category->slug,
            ];
        })->toArray();
    }

    public function save()
    {
        $ids = collect($this->categories)
            ->map(function (array $category) {
                if (isset($category['id'])) {
                    $rating = RatingCategory::find($category['id']);
                    $rating->update([
                        'name' => $category['name'],
                        'icon' => $category['icon'],
                    ]);

                    return $rating;
                }

                return RatingCategory::create(['name' => $category['name']]);
            })
            ->pluck('id');

        RatingCategory::whereNotIn('id', $ids)->delete();

        RebuildCache::dispatch();

        $this->toast('Rating categories saved successfully');
    }
}
