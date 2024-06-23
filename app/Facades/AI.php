<?php

namespace App\Facades;

use Api\Clients\OpenRouter;
use Illuminate\Support\Facades\Facade;

class AI extends Facade
{
    protected static function getFacadeAccessor()
    {
        return OpenRouter::class;
    }
}
