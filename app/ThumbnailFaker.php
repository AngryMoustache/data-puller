<?php

namespace App;

class ThumbnailFaker
{
    public function __construct(public string $thumbnail)
    {
        //
    }

    public function format()
    {
        return $this->thumbnail;
    }
}
