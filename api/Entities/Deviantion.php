<?php

namespace Api\Entities;

use Api\Clients\DeviantArt;
use Api\Entities\Media\Image;
use Api\Entities\Media\Video;
use App\Models\Artist;
use Illuminate\Support\Collection;

class Deviantion extends Pullable
{
    public string $deviantId;

    public function __construct($pull)
    {
        $this->deviantId = $pull['deviationid'];
        $this->name = translate_japanese($pull['title'])->limit(50);
        $this->source = $pull['url'];
        $this->artist = Artist::guess($pull['author']['username'] ?? '');

        if (isset($pull['videos'])) {
            $source = collect($pull['videos'])
                ->sortByDesc('filesize')
                ->where('filesize', '<', 10000000)
                ->first();

            $thumb = Image::make()
                ->source($pull['preview']['src'])
                ->size($pull['preview']['width'], $pull['preview']['height']);

            $this->media = Video::make()
                ->previewImage($thumb)
                ->filesize($source['filesize'])
                ->source($source['src']);
        } else {
            $this->media = Image::make()
                ->source($pull['content']['src'])
                ->size($pull['content']['width'], $pull['content']['height']);
        }
    }

    public function tags(): Collection
    {
        $tags = (new DeviantArt($this->origin))->getTags($this->deviantId);

        if (! isset($tags['metadata'][0]['tags'])) {
            return collect();
        }

        return collect($tags['metadata'][0]['tags'])
            ->pluck('tag_name')
            ->mapInto(Tag::class);
    }
}
