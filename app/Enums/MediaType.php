<?php

namespace App\Enums;

use Illuminate\Support\Collection;

enum MediaType: string
{
    case ALL = 'all';
    case VIDEO = 'video';
    case IMAGE = 'image';

    public function label()
    {
        return match ($this) {
            self::ALL => 'Show all',
            self::VIDEO => 'Only videos',
            self::IMAGE => 'Only images',
        };
    }

    public function filter(Collection $items)
    {
        if ($this === self::ALL) {
            return $items;
        }

        return $items->reject(fn ($item) => $item['media_type'][$this->value] ?? false);
    }

    public static function list()
    {
        return collect(self::cases())->mapWithKeys(fn ($value) => [
            $value->value => $value->label(),
        ]);
    }
}
