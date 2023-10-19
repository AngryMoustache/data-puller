<?php

namespace Api\Entities\Media;

use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Image extends Media
{
    public function save()
    {
        $filename = Str::of($this->src)->before('?')->afterLast('/');
        $name = $this->name ?? $filename->before('.');
        $extension = $filename->after('.');

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

        $folder = "mobileart/public/attachments/{$attachment->id}";

        // Pixiv requires some extra authorization to download the image
        $context = null;
        if (Str::contains($this->src, 'i.pximg.net')) {
            $context = stream_context_create(['http' => [
                'method' => 'GET',
                'header' => 'Referer: https://pixiv.net'
            ]]);
        }

        $file = file_get_contents($this->src, false, $context);

        $tmpPath = "tmp--{$filename}";
        file_put_contents($tmpPath, $file);
        @Storage::disk('nas-media')->makeDirectory($folder, 'public');
        Storage::disk('nas-media')->putFileAs($folder, $tmpPath, $filename);
        // unlink($tmpPath);

        // Generate thumb format
        $attachment->format('thumb');

        // Fill in some extra data
        $filesize = getimagesize($attachment->getUrl("{$attachment->id}/{$filename}"));
        $response = Storage::disk('nas-media')->response("${folder}/{$filename}");

        $attachment->size = $response->headers->get('content-length');
        $attachment->mime_type = $filesize['mime'];
        $attachment->width = empty($this->width) ? $filesize[0] : $this->width;
        $attachment->height = empty($this->height) ? $filesize[1] : $this->height;
        $attachment->saveQuietly();

        return $attachment;
    }
}
