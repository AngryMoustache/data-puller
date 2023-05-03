<?php

namespace App\Console\Commands;

use App\Pulls;
use Illuminate\Console\Command;

class RebuildCache extends Command
{
    public $signature = 'rebuild:cache';

    public function handle()
    {
        Pulls::build(true);
    }
}
