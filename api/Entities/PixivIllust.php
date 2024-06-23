<?php

namespace Api\Entities;

use Api\Clients\Pixiv;
use Api\Entities\Media\Image;
use Api\Entities\Media\Ugoira;
use App\Models\Artist;

class PixivIllust extends Pullable
{
    public function __construct($pull)
    {
        $this->name = translate_japanese($pull['title'], $pull['id'])->limit(50);
        if ($pull['title']) {
            $this->originalName = $pull['title'];
        }

        $this->source = "https://www.pixiv.net/en/artworks/{$pull['id']}";

        $this->artist = Artist::guess(
            $pull['user']['name'] ?? $pull['user']['account'] ?? '',
        );

        $this->tags = collect($pull['tags'])
            ->map(fn (array $tag) => $tag['translated_name'] ?? $tag['name'] ?? null)
            ->filter()
            ->mapInto(Tag::class);

        if ($pull['type'] === 'ugoira') {
            $media = [(new Pixiv)->ugoira($pull['id'])];
            $this->tags->push(new Tag('Animated GIF'));
        } else {
            $media = $pull['meta_pages'];
            if ($media === []) {
                $media = [['image_urls' => [
                    'original' => $pull['meta_single_page']['original_image_url']]
                ]];
            }
        }

        $this->media = collect($media);
        $this->media = $this->media->map(function ($media, $key) {
            $name = $this->name;
            if ($this->media->count() > 1) {
                $name .= ' - ' . ($key + 1);
            }

            if (is_object($media) && get_class($media) === Ugoira::class) {
                return $media->name($name);
            }

            return Image::make()
                ->source($media['image_urls']['original'])
                ->name($name);
        });
    }
}
