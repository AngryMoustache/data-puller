<?php

namespace App;

use App\Models\Attachment;

class Site
{
    public null | string $title = null;

    public function getTitle()
    {
        return $this->title ?? config('app.name');
    }

    public function title(string $title)
    {
        $this->title = $title . ' | ' . config('app.name');
    }

    public function randomImage()
    {
        return 'https://mobileart.dev/storage/attachments/958/FkBqx_VXgAQdaQI.jpg';
        // return Attachment::inRandomOrder()
        //     ->whereRaw('height > width')
        //     ->first();
    }

    public static function bytesToHuman($bytes)
    {
        $units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
