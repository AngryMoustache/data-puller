<?php

namespace App\Console\Commands;

use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateLongNames extends Command
{
    public $signature = 'sync:long-names';

    public function handle()
    {
        Tag::get()->each(function ($tag) {
            $tag->long_name = $tag->generateLongName();
            $tag->slug = Str::slug($tag->long_name);
            $tag->saveQuietly();
        });
    }
}
