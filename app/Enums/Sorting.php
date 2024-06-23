<?php

namespace App\Enums;

use Illuminate\Support\Collection;

enum Sorting: string
{
    case VERDICT = 'verdict';
    case POPULAR = 'popular';
    case RATING = 'rating';
    case RATING_CATEGORY = 'rating-category';
    case RANDOM = 'random';

    public function label()
    {
        return match ($this) {
            self::VERDICT => 'Pull date',
            self::POPULAR => 'Popularity',
            self::RATING => 'Average rating',
            self::RATING_CATEGORY => 'Rating category',
            self::RANDOM => 'Randomized',
        };
    }

    public function sortCollection(Collection $collection, SortDir $direction, array $extra = [])
    {
        $desc = $direction->isDescending();

        return match ($this) {
            self::VERDICT => $collection->sortBy('verdict_at', descending: $desc),
            self::POPULAR => $collection->sortBy('views', descending: $desc),
            self::RATING => $collection
                ->where('ratings.overall', '>', 0)
                ->sortBy('ratings.overall', descending: $desc),
            self::RATING_CATEGORY => $collection
                ->where("ratings.{$extra['category']}", '>', 0)
                ->sortBy("ratings.{$extra['category']}", descending: $desc),
            self::RANDOM => $collection->shuffle(),
        };
    }

    public function isRandomizer()
    {
        return match ($this) {
            self::RANDOM => true,
            default => false,
        };
    }

    public function hasCategory()
    {
        return match ($this) {
            self::RATING_CATEGORY => true,
            default => false,
        };
    }

    public static function list()
    {
        return collect(self::cases())->mapWithKeys(fn ($value) => [
            $value->value => $value->label(),
        ]);
    }

    public static function default()
    {
        return self::VERDICT;
    }
}
