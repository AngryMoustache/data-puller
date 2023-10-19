<?php

namespace App\Providers;

use App\Filesystem\NasAdapter;
use App\Models\Pull;
use App\Observers\PullObserver;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        app()->singleton('site', fn () => new \App\Site);
    }

    public function boot()
    {
        Pull::observe(PullObserver::class);

        Storage::extend('nas', function (Application $app, array $config) {
            $adapter = new NasAdapter;
            $filesystem = new Filesystem($adapter, $config);
            return new FilesystemAdapter($filesystem, $adapter, $config);
        });
    }
}
