<?php

namespace Api\Entities;

use Api\Entities\Media\Image;

class AIGeneration extends Pullable
{
    public function __construct($pull)
    {
        $this->name = collect($pull['tags'])->join(' ');
        $this->source = md5(now()->timestamp);

        $this->media = Image::make()
            ->source($pull['media']['source'])
            ->size(
                $pull['media']['width'],
                $pull['media']['height']
            );
    }
}
