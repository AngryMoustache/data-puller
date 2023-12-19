<?php

namespace Api\Entities;

use Api\Entities\Media\Image;
use App\Models\Artist;
use Illuminate\Support\Str;

class BlueskyPost extends Pullable
{
    public function __construct($pull)
    {
        $this->name = $this->checkJapanese($pull['record']['text'], $pull['cid'])->limit(50);

        $this->source = $pull['uri'];
        $this->artist = Artist::guess(Str::before(($pull['author']['handle'] ?? ''), '.bsky.social'));

        $this->media = collect($pull['embed']['images'] ?? []);
        $this->media = $this->media->map(function ($media, $key) {
            $name = $this->name;
            if ($this->media->count() > 1) {
                $name .= ' - ' . ($key + 1);
            }

            return Image::make()
                ->source($media['fullsize'] ?? dd($media))
                ->size($media['aspectRatio']['width'] ?? 0, $media['aspectRatio']['height'] ?? 0)
                ->name($name);
        })->filter();
    }
}
