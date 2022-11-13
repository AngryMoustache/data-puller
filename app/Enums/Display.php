<?php

namespace App\Enums;

enum Display: string
{
    case COMPACT = 'compact';
    case CARD = 'card';
    // case LIST = 'list';

    public function label()
    {
        return match ($this) {
            self::COMPACT => 'Compact view',
            self::CARD => 'Cards view',
            // self::LIST => 'List view',
        };
    }

    public static function list()
    {
        return collect(self::cases())->mapWithKeys(fn ($value) => [
            $value->value => $value->label()
        ]);
    }
}
