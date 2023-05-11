<?php

namespace Api\Entities;

use Api\Entities\Media\Image;
use Api\Entities\Media\Video;
use App\Models\Artist;

class Deviantion extends Pullable
{
    public function __construct($pull)
    {
        $this->name = $this->checkJapanese($pull['title'])->limit(50);
        $this->source = $pull['url'];
        $this->artist = Artist::guess($pull['author']['username'] ?? '');

        if (isset($pull['videos'])) {
            $source = collect($pull['videos'])
                ->sortByDesc('filesize')
                ->where('filesize', '<', 10000000)
                ->first()['src'];

            $thumb = Image::make()
                ->source($pull['preview']['src'])
                ->size($pull['preview']['width'], $pull['preview']['height']);

            $this->media = Video::make()
                ->previewImage($thumb)
                ->source($source);
        } else {
            $this->media = Image::make()
                ->source($pull['content']['src'])
                ->size($pull['content']['width'], $pull['content']['height']);
        }
    }
}
