<?php

namespace Api\Entities\Media;

use AngryMoustache\Media\Models\Attachment;
use App\Models\Video as ModelsVideo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Video extends Media
{
    public ?Attachment $preview = null;

    public function previewImage(Image $image)
    {
        $this->preview = $image->save();

        return $this;
    }

    public function save()
    {
        // Create the video itself and link the thumbnail
        $filename = Str::of($this->video_src)->before('?')->afterLast('/');
        $name = $filename->before('.');
        $extension = $filename->afterLast('.');

        $video = ModelsVideo::firstOrCreate([
            'preview_id' => $this->preview?->id,
            'name' => (string) $name,
            'filename' => (string) $filename,
            'extension' => (string) $extension,
        ]);

        // Avoid saving the video again if we already have it
        if (! $video->wasRecentlyCreated) {
           return $video;
        }

        // Save the file on the disk
        $path = "public/videos/{$video->id}/";
        Storage::putFileAs($path, $this->video_src, (string) $filename);
        $video->size = filesize($video->fullPath());
        $video->saveQuietly();

        return $video;
    }
}
