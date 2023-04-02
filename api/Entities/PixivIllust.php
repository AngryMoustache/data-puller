<?php

namespace Api\Entities;

use Api\Entities\Media\Image;

class PixivIllust extends Pullable
{
    public function __construct($pull)
    {
        $media = $pull['meta_pages'];
        if ($media === []) {
            $media = [['image_urls' => [
                'original' => $pull['meta_single_page']['original_image_url']]
            ]];
        }

        $this->name = $this->checkJapanese($pull['title'], $pull['id'])->limit(50);
        $this->source = "https://www.pixiv.net/en/artworks/{$pull['id']}";
        $this->media = collect($media);
        $this->artist = $this->getArtist($pull['user']['account'] ?? '');

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
