<?php

namespace App;

use App\Models\Slideshow;

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
        return 'https://media.mobileart.dev/62cea60c-0acf-4e34-8e21-003e86db36d4/ac63dbff-062d-4ae1-9e0e-14da06d83063';
    }

    public static function bytesToHuman($bytes)
    {
        $units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public static function slideshow(iterable $pulls)
    {
        return route('slideshow.show',  Slideshow::updateOrCreate(
            ['md5' => md5(collect($pulls)->toJson())],
            ['ids' => $pulls],
        ));
    }
}
