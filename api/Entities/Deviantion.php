<?php

namespace Api\Entities;

use Api\Entities\Media\Image;
use App\Models\Artist;

class Deviantion extends Pullable
{
    public function __construct($pull)
    {
        $this->name = $this->checkJapanese($pull['title'])->limit(50);
        $this->source = $pull['url'];
        $this->artist = Artist::guess($pull['author']['username'] ?? '');

        $this->media = Image::make()
            ->source($pull['content']['src'])
            ->size($pull['content']['width'], $pull['content']['height']);
    }
}
