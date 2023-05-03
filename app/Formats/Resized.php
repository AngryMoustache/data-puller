<?php

namespace App\Formats;

use AngryMoustache\Media\Formats\Format;
use Spatie\Image\Image;

class Resized extends Format
{
    public static function render(Image $image)
    {
        return $image->width(600);
    }
}
