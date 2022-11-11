<?php

namespace Api\Entities;

use Api\Entities\Media\Image;

class PixivIllust extends Pullable
{
    public function __construct($pull)
    {
        $this->name = $pull['title'];
        $this->source = "https://www.pixiv.net/en/artworks/{$pull['id']}";
        $this->media = collect($pull['meta_pages']);

        $this->media = $this->media->map(function ($media, $key) {
            $name = $this->name;
            if ($this->media->count() > 1) {
                $name .= ' - ' . ($key + 1);
            }

            return Image::make()
                ->source($media['image_urls']['original'])
                ->name($name);
        });
    }
}
