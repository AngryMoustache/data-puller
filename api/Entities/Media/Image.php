<?php

namespace Api\Entities\Media;

use App\Filesystem\MediaServer;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Image extends Media
{
    public bool $isBase64 = false;

    public function save()
    {
        $filename = $this->filename ?? Str::of($this->src)->before('?')->afterLast('/');
        $name = $this->name ?? $filename->before('.');
        $extension = $this->extension ?? $filename->after('.');

        $attachment = Attachment::withoutGlobalScopes()->firstOrCreate([
            'original_name' => (string) $filename,
            'extension' => (string) $extension,
            'md5' => @md5_file($this->src),
        ], [
            'alt_name' => $name,
            'disk' => config('media.default-disk', 'public'),
            'folder_location' => 'pulls',
        ]);

        // Avoid saving the image again if we already have it
        if (! $attachment->wasRecentlyCreated) {
           return $attachment;
        }

        // Pixiv requires some extra authorization to download the image
        $context = null;
        if (Str::contains($this->src, 'i.pximg.net')) {
            $context = stream_context_create(['http' => [
                'method' => 'GET',
                'header' => 'Referer: https://pixiv.net'
            ]]);
        }

        $file = $this->isBase64
            ? base64_decode($this->src)
            : file_get_contents($this->src, false, $context);

        // Save the file to the media server
        MediaServer::upload($file, $attachment->uuid, $filename);

        // Generate thumb format
        $attachment->format('thumb');

        // Fill in some extra data
        $filesize = $this->filesize ?? getimagesize($attachment->path());

        $attachment->mime_type = $filesize['mime'];
        $attachment->width = empty($this->width) ? $filesize[0] : $this->width;
        $attachment->height = empty($this->height) ? $filesize[1] : $this->height;
        $attachment->saveQuietly();

        return $attachment;
    }

    public function base64(string $base64)
    {
        $this->src = $base64;
        $this->isBase64 = true;

        return $this;
    }
}
