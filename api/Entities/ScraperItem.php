<?php

namespace Api\Entities;

use Api\Entities\Media\Image;
use App\Enums\Origin;

class ScraperItem extends Pullable
{
    public function __construct($favorite)
    {
        $this->name = 'Scraper favorite ' . $favorite['id'];
        $this->artist = $this->getArtist($favorite['owner'] ?? '');
        $this->source = config('clients.scraper.detail_url') . $favorite['id'];

        $this->media = Image::make()
            ->source($favorite['file_url'])
            ->size($favorite['width'], $favorite['height']);
    }
}
