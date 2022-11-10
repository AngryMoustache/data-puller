<?php

namespace App\Enums;

enum Sorting: string
{
    case NEWEST = 'newest';
    case OLDEST = 'oldest';
    case POPULAR = 'most-viewed';
    case UNPOPULAR = 'least-viewed';
    case RANDOM = 'randomized';

    public function label()
    {
        return match ($this) {
            self::NEWEST => 'Newest',
            self::OLDEST => 'Oldest',
            self::POPULAR => 'Most Viewed',
            self::UNPOPULAR => 'Least Viewed',
            self::RANDOM => 'Randomized',
        };
    }

    public function sortCollection($collection)
    {
        return match ($this) {
            self::NEWEST => $collection->sortBy('verdict_at'),
            self::OLDEST => $collection->sortByDesc('verdict_at'),
            self::POPULAR => $collection->sortBy('views'),
            self::UNPOPULAR => $collection->sortByDesc('views'),
            self::RANDOM => $collection->shuffle(),
        };
    }

    public static function list()
    {
        return collect(self::cases())->mapWithKeys(fn ($value) => [
            $value->value => $value->label()
        ]);
    }
}
