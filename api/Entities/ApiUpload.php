<?php

namespace Api\Entities;

use Api\Entities\Media\Image;

class ApiUpload extends Pullable
{
    public function __construct($pull)
    {
        $this->name = $pull['name'] ?? $pull['images'][0]['name'] ?? 'No name given.';
        $this->source = md5(now()->timestamp);
        $this->artist = $this->getArtist($pull['artist']);

        $this->media = collect($pull['images'] ?? [])->map(function (array $image) {
            return Image::make()
                ->source($image['url'])
                ->size($image['width'], $image['height']);
        });
    }
}
