<?php

namespace App\Providers;

use App\Models\Pull;
use App\Observers\PullObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(\App\PullCache::class, function () {
            return new \App\PullCache;
        });
    }

    public function boot()
    {
        Pull::observe(PullObserver::class);
    }
}
