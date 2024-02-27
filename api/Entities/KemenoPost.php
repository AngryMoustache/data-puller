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
        dd($pull);
        $this->name = $this->checkJapanese($pull['title'])->limit(50);
        $this->source = $url;

        $artist = Http::get(Str::of($url)->beforeLast('/post/')->append('/profile'))->collect();
        $this->artist = Artist::guess($artist['name'] ?? $pull['user'] ?? '');

        $this->media = collect($pull['attachments'])
            ->merge($pull['file'] ?? null)
            ->filter()
            ->dd()
            ->map(function ($media) {
                return Image::make()
                    ->source("https://kemono.su{$media['path']}")
                    ->name($media['name']);
            })
            ->filter();
    }
}
