<?php

namespace Api\Entities;

use Api\Entities\Media\Image;
use App\Models\Artist;

class ScraperItem extends Pullable
{
    public function __construct($favorite)
    {
        $this->name = 'Scraper favorite ' . $favorite['id'];
        $this->artist = Artist::guess($favorite['owner'] ?? '');
        $this->source = config('clients.scraper.source_url') . $favorite['id'];

        $this->tags = collect(explode(' ', $favorite['tags']))
            ->mapInto(Tag::class);

        $this->media = Image::make()
            ->source($favorite['file_url'])
            ->size($favorite['width'], $favorite['height']);
    }
}
