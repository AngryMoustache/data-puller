<?php

namespace App\Providers;

use App\Models\Pull;
use App\Observers\PullObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        app()->singleton('site', fn () => new \App\Site);
    }

    public function boot()
    {
        Pull::observe(PullObserver::class);
    }
}
