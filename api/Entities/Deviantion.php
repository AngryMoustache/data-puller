<?php

namespace Api\Entities;

use App\Enums\Origin;

class Deviantion extends Pullable
{
    public function __construct($pull)
    {
        $this->name = $pull['title'];
        $this->source = $pull['url'];

        $this->media = new Media([
            'url' => $pull['content']['src'],
            'width' => $pull['content']['width'],
            'height' => $pull['content']['height'],
            'type' => 'image',
        ]);
    }
}
