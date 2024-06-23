<?php

namespace App\Enums;

enum SortDir: string
{
    case ASC = 'asc';
    case DESC = 'desc';

    public function label(): string
    {
        return match ($this) {
            self::ASC => 'Ascending',
            self::DESC => 'Descending',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::ASC => 'heroicon-o-arrow-long-up',
            self::DESC => 'heroicon-o-arrow-long-down',
        };
    }

    public function isDescending(): bool
    {
        return $this === self::DESC;
    }

    public function isAscending(): bool
    {
        return $this === self::ASC;
    }

    public static function default(): SortDir
    {
        return self::DESC;
    }
}
