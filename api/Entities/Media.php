<?php

namespace Api\Entities;

use AngryMoustache\Media\Models\Attachment;
use App\Enums\MediaType;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Media
{
    public string $name;
    public string $src;
    public ?string $video_src;
    public ?MediaType $type;

    public int $width = 0;
    public int $height = 0;

    public function __construct($data)
    {
        $this->name = md5(now());
        $this->type = MediaType::guess($data['type']);

        $this->width = $data['width'] ?? 0;
        $this->height = $data['height'] ?? 0;

        // Check if it's an image or video
        $this->src = $data['url'] ?? $data['src'] ?? '';
        if ($this->src === '') {
            $this->src = $data['preview_image_url'];
            $this->video_src = collect($data['variants'])
                ->where('content_type', 'video/mp4')
                ->last()['url'] ?? null;
        }
    }

    public function save()
    {
        return match ($this->type) {
            MediaType::IMAGE => $this->saveAsImage(),
            MediaType::VIDEO => $this->saveAsVideo(),
        };
    }

    public function saveAsImage()
    {
        $filename = Str::of($this->src)->before('?')->afterLast('/');
        $name = $this->name ?? $filename->before('.');
        $extension = $filename->after('.');
        $filesize = getimagesize($this->src);

        $attachment = Attachment::withoutGlobalScopes()->firstOrCreate([
            'original_name' => $filename,
            'alt_name' => $name,
            'extension' => $extension,
            'mime_type' => $filesize['mime'],
            'width' => $filesize[0],
            'height' => $filesize[1],
            'folder_location' => 'pulls',
        ], [
            'disk' => config('media.default-disk', 'public'),
        ]);

        // Avoid saving the image again if we already have it
        if (! $attachment->wasRecentlyCreated) {
           return $attachment;
        }

        $folder = "public/attachments/{$attachment->id}";
        Storage::putFileAs($folder, $this->src, $filename);

        $response = Storage::response("${folder}/{$filename}");
        $attachment->size = $response->headers->get('content-length');
        $attachment->saveQuietly();

        return $attachment;
    }

    public function saveAsVideo()
    {
        // Create the thumbnail
        $preview = (new self([
            'name' => "{$this->name} - preview",
            'src' => $this->src,
            'type' => MediaType::IMAGE->value,
        ]))->save();

        // Create the video itself and link the thumbnail
        $filename = Str::of($this->video_src)->before('?')->afterLast('/');
        $name = $filename->before('.');
        $extension = $filename->afterLast('.');

        $video = Video::firstOrCreate([
            'preview_id' => $preview->id,
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
