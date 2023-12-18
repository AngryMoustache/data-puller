<?php

namespace Api\Entities\Media;

use App\Models\Video as ModelsVideo;
use Illuminate\Support\Facades\Storage;
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
        ]);

        // Avoid saving the video again if we already have it
        if (! $video->wasRecentlyCreated) {
           return $video;
        }

        $folder = "mobileart/public/videos/{$video->id}/";

        $tmpPath = "tmp--{$filename}";
        $file = file_get_contents($this->src);
        $tmpFile = file_put_contents($tmpPath, $file);

        // Save the file on the NAS
        Storage::disk('nas-media')->makeDirectory($folder, 'public');
        Storage::disk('nas-media')->putFileAs($folder, $tmpPath, (string) $filename);

        unlink($tmpPath);

        $response = Storage::disk('nas-media')->response("${folder}/{$filename}");
        $video->size = $response->headers->get('content-length');
        $video->saveQuietly();

        return $video;
    }
}
