<?php

namespace Api\Entities\Media;

use App\Filesystem\MediaServer;
use App\Models\Video as ModelsVideo;
use Illuminate\Support\Str;

class Video extends Media
{
    public ?Image $preview = null;

    public function previewImage(Image $image)
    {
        $this->preview = $image;

        return $this;
    }

    public function save()
    {
        // Create the video itself and link the thumbnail
        $filename = $this->filename ?? Str::of($this->src)->before('?')->afterLast('/');
        $name = $this->name ?? $filename->before('.');
        $extension = $this->extension ?? $filename->afterLast('.');

        $video = ModelsVideo::firstOrCreate([
            'preview_id' => $this->preview->save()?->id,
            'alt_name' => (string) $name,
            'original_name' => (string) $filename,
            'extension' => (string) $extension,
            'size' => $this->filesize,
        ]);

        // Avoid saving the video again if we already have it
        if (! $video->wasRecentlyCreated) {
           return $video;
        }

        // Save the file to the media server
        $file = file_get_contents($this->src);
        MediaServer::upload($file, $video->uuid, $filename, 'videos');

        return $video;
    }
}
