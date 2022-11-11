<?php

namespace Api\Entities;

use Api\Entities\Media\Image;

class Deviantion extends Pullable
{
    public function __construct($pull)
    {
        $this->name = $pull['title'];
        $this->source = $pull['url'];

        $this->media = Image::make()
            ->source($pull['content']['src'])
            ->size($pull['content']['width'], $pull['content']['height']);
    }
}
