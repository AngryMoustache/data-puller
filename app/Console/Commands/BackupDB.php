<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\DbDumper\Databases\MySql as DbDumper;

class BackupDB extends Command
{
    public $signature = 'backup:db';

    public function handle()
    {
        DbDumper::create()
            ->setDbName(config('database.connections.mysql.database'))
            ->setUserName(config('database.connections.mysql.username'))
            ->setPassword(config('database.connections.mysql.password'))
            ->dumpToFile('dump.sql');

        Storage::disk('nas-backup')->put(
            'backup/mobileart.dev--' . now()->format('Y-m-d') . '.sql',
            file_get_contents('dump.sql')
        );

        unlink('dump.sql');
    }
}
