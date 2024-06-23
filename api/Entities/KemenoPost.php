<?php

namespace Api\Entities;

use Api\Entities\Media\Image;
use App\Models\Artist;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class KemenoPost extends Pullable
{
    public function __construct($pull, $url)
    {
        $this->name = translate_japanese($pull['title'])->limit(50);
        $this->source = $url;

        $artist = Http::get(Str::of($url)->beforeLast('/post/')->append('/profile'))->collect();
        $this->artist = Artist::guess($artist['name'] ?? $pull['user'] ?? '');

        $this->media = collect($pull['attachments'])
            ->prepend($pull['file'] ?? null)
            ->filter()
            ->map(function (array $media) {
                return Image::make()
                    ->source("https://kemono.su{$media['path']}")
                    ->name($media['name']);
            })
            ->filter();
    }
}
