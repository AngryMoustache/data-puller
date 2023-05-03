<?php

namespace App\Enums;

enum Status: string
{
    case ONLINE = 'online';
    case OFFLINE = 'offline';
    case PENDING = 'pending';

    public static function list()
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($value) => [$value->value => $value->name]);
    }

    public function style()
    {
        return match ($this) {
            self::ONLINE => 'background: #40d949 !important;',
            self::OFFLINE => 'background: #d94040 !important;',
            self::PENDING => 'background: #ede90f !important; color: #3b3b3b !important;',
        };
    }
}
