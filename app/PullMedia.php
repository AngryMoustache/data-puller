<?php

namespace App;

use AngryMoustache\Media\Models\Attachment;
use App\Models\Video;
use Illuminate\Support\Str;

class PullMedia
{
    public string $id;

    public Attachment $image;

    public function __construct(public Video | Attachment $media)
    {
        $this->id = $media::class . ':' . $media->id;

        $this->image = match (true) {
            $media instanceof Attachment => $media,
            $media instanceof Video => $media->preview,
        };
    }

    public function format(string $format)
    {
        return $this->image->format($format);
    }

    public function jsonId()
    {
        return json_encode($this->id);
    }

    public function toJson()
    {
        return [
            'id' => $this->id,
            'name' => Str::limit($this->image->original_name, 15),
            'width' => $this->image->width,
            'height' => $this->image->height,
            'thumbnail' => $this->image->format('thumb'),
            'is_thumbnail' => (bool) ($this->media->pivot?->is_thumbnail ?? false),
        ];
    }
}
