<?php

namespace App\Console\Commands;

use App\Models\Origin;
use Illuminate\Console\Command;

class SyncOrigins extends Command
{
    public $signature = 'sync:origins {origin?}';

    public function handle()
    {
        $origin = $this->argument('origin');

        Origin::online()
            ->when($origin, fn ($query) => $query->where('id', $origin))
            ->get()
            ->filter(fn (Origin $origin) => $origin->type->canPull())
            ->each(fn (Origin $origin) => $origin->pull());
    }
}
