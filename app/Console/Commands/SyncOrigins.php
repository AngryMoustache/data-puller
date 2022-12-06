<?php

namespace App\Console\Commands;

use App\Models\Origin;
use Illuminate\Console\Command;

class SyncOrigins extends Command
{
    public $signature = 'sync:origins';

    public function handle()
    {
        Origin::get()->each(fn ($origin) => $origin->pull());
    }
}
