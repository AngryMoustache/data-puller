<?php

namespace Api\Entities;

use Api\Entities\Media\Image;
use Api\Entities\Media\Video;

class Tweet extends Pullable
{
    public function __construct($pull)
    {
        $this->name = $pull['id'];
        $this->source = $pull['entities']['urls'][0]['url'];
        $this->media = $pull['attachments'];

        $this->media = $this->media->map(function ($media, $key) {
            $name = $this->name;
            if ($this->media->count() > 1) {
                $name .= ' - ' . ($key + 1);
            }

            if (in_array(($media['type'] ?? null), ['video', 'animated_gif'])) {
                $video = collect($media)
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
