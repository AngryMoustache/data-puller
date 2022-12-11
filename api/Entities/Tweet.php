<?php

namespace Api\Entities;

use Api\Entities\Media\Image;
use Api\Entities\Media\Video;
use Illuminate\Support\Str;

class Tweet extends Pullable
{
    public function __construct($pull)
    {
        $this->name = $this->checkJapanese(
            Str::beforeLast($pull['text'], 'https://t.co/'),
            $pull['id']
        )->limit(50);

        $this->source = $pull['entities']['urls'][0]['url'];
        $this->media = $pull['attachments'];
        $this->artist = $pull['author_id'] ?? '';

        $this->media = $this->media->map(function ($media, $key) {
            $name = $this->name;
            if ($this->media->count() > 1) {
                $name .= ' - ' . ($key + 1);
            }

            if (in_array(($media['type'] ?? null), ['video', 'animated_gif'])) {
                $video = collect($media['variants'])
                    ->where('content_type', 'video/mp4')
                    ->where('bit_rate', '<', 1000000)
                    ->first()['url'] ?? null;

                if (! $video) {
                    return null;
                }

                return Video::make()
                    ->previewImage(Image::make()->source($media['preview_image_url']))
                    ->source($video);
            }

            return Image::make()
                ->source($media['url'] ?? dd($media))
                ->size($media['width'], $media['height'])
                ->name($name);
        })->filter();
    }
}
