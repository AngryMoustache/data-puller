<?php

namespace App\Enums;

enum Status: string
{
    case CREATING = 'creating';
    case ONLINE = 'online';
    case OFFLINE = 'offline';
    case PENDING = 'pending';

    public static function list()
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($value) => [$value->value => $value->name]);
    }
}
