<?php

namespace App\Console\Commands;

use Api\Jobs\RebuildTagNames as JobsRebuildTagNames;
use Illuminate\Console\Command;

class RebuildTagNames extends Command
{
    public $signature = 'rebuild:tag-names';

    public function handle()
    {
        JobsRebuildTagNames::dispatch();
    }
}
