<?php

namespace App;

use AngryMoustache\Media\Models\Attachment;
use App\Models\Video;
use Illuminate\Support\Str;

class PullMedia
{
    public int|string $modelId;
    public string $id;
    public bool $isVideo;

    public null | Attachment $image;

    const VIDEO_EXTENSIONS = [
        'mp4',
        'gif',
    ];

    public function __construct(public Video | Attachment $media)
    {
        $this->modelId = $media->id;
        $this->id = $media::class . ':' . $media->id;

        $this->isVideo = in_array(
            $media->extension,
            self::VIDEO_EXTENSIONS,
        );

        $this->image = match (true) {
            $media instanceof Attachment => $media,
            $media instanceof Video => $media->preview,
        };
    }

    public function format(string $format = 'thumb')
    {
        return $this->image?->format($format);
    }

    public function jsonId()
    {
        return json_encode($this->id);
    }

    public function toJson()
    {
        return [
            'id' => $this->id,
            'name' => Str::limit($this->image?->original_name ?? 'No name', 15),
            'width' => $this->image?->width,
            'height' => $this->image?->height,
            'thumbnail' => $this->format('thumb'),
        ];
    }
}
