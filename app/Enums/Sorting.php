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
            self::NEWEST => 'Newest first',
            self::OLDEST => 'Oldest first',
            self::POPULAR => 'Most Viewed first',
            self::UNPOPULAR => 'Least Viewed first',
            self::RANDOM => 'Randomized',
        };
    }

    public function sortCollection($collection)
    {
        return match ($this) {
            self::NEWEST => $collection->sortByDesc('verdict_at'),
            self::OLDEST => $collection->sortBy('verdict_at'),
            self::POPULAR => $collection->sortByDesc('views'),
            self::UNPOPULAR => $collection->sortBy('views'),
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

    public static function list()
    {
        return collect(self::cases())->mapWithKeys(fn ($value) => [
            $value->value => $value->label()
        ]);
    }
}
