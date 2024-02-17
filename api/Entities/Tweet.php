<?php

namespace Api\Entities;

use Api\Entities\Media\Image;
use Api\Entities\Media\Video;
use App\Models\Artist;
use Illuminate\Support\Str;

class Tweet extends Pullable
{
    public function __construct($pull)
    {
        $this->name = $this->checkJapanese(
            Str::beforeLast($pull['legacy']['full_text'], 'https://t.co/'),
            $pull['rest_id']
        )->limit(50);

        $this->source = Str::afterLast($pull['legacy']['full_text'], ' ');
        $this->media = collect($pull['legacy']['extended_entities']['media']);
        $this->artist = Artist::guess($pull['core']['user_result']['result']['legacy']['screen_name'] ?? '');

        $this->media = $this->media->map(function ($media) {
            if (in_array(($media['type'] ?? null), ['video', 'animated_gif'])) {
                $video = collect($media['video_info']['variants'] ?? [])
                    ->where('content_type', 'video/mp4')
                    ->where('bitrate', '<', 2177000)
                    ->sortByDesc('bitrate')
                    ->first();

                if (! ($video['url'] ?? null)) {
                    return null;
                }

                return Video::make()
                    ->previewImage(Image::make()->source($media['media_url_https']))
                    ->filesize($video['bitrate'] ?? 0)
                    ->source($video['url']);
            }

            return Image::make()
                ->source($media['media_url_https'])
                ->size($media['sizes']['large']['w'] ?? 0, $media['sizes']['large']['h'] ?? 0)
                ->name($media['media_key']);
        })->filter();
    }
}
