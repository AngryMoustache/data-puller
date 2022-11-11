<?php

namespace Api\Entities\Media;

use AngryMoustache\Media\Models\Attachment;
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
            'original_name' => $filename,
            'alt_name' => $name,
            'extension' => $extension,
            'folder_location' => 'pulls',
        ], [
            'disk' => config('media.default-disk', 'public'),
        ]);

        // Avoid saving the image again if we already have it
        if (! $attachment->wasRecentlyCreated) {
           return $attachment;
        }

        // Pixiv requires some extra authorization to download the image
        if (Str::contains($this->src, 'i.pximg.net')) {
            $folder = "public/attachments/{$attachment->id}";

            $context = stream_context_create(['http' => [
                'method' => 'GET',
                'header' => 'Referer: https://pixiv.net'
            ]]);

            $file = file_get_contents($this->src, false, $context);

            Storage::put("${folder}/{$filename}", $file);
        } else {
            $folder = "public/attachments/{$attachment->id}";
            Storage::putFileAs($folder, $this->src, $filename);
        }

        // Fill in some extra data
        $filesize = getimagesize($attachment->path());
        $response = Storage::response("${folder}/{$filename}");

        $attachment->size = $response->headers->get('content-length');
        $attachment->mime_type = $filesize['mime'];
        $attachment->width = $this->width ?? $filesize[0];
        $attachment->height = $this->height ?? $filesize[1];
        $attachment->saveQuietly();

        return $attachment;
    }
}
