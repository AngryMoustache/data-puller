<?php

namespace App\Facades;

use App\PullCache as AppPullCache;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Support\Collection build()
 * @method static void rebuild()
 * @method static \Illuminate\Support\Collection get()
 *
 * @see \App\PullCache
 */
class PullCache extends Facade
{
    public static function getFacadeAccessor()
    {
        return AppPullCache::class;
    }
}
