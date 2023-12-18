<?php

namespace App\Enums;

use App\Models\Attachment;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

enum TaskType: string
{
    case IMAGE = 'image';
    case VIDEO = 'video';

    public function label(): string
    {
        return match ($this) {
            self::IMAGE => 'Image',
            self::VIDEO => 'Video',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::IMAGE => 'heroicon-o-photo',
            self::VIDEO => 'heroicon-o-film',
        };
    }

    public function folder(): string
    {
        return match ($this) {
            self::IMAGE => 'attachments',
            self::VIDEO => 'videos',
        };
    }

    public function createMedia(string $path): Attachment|Video
    {
        return match ($this) {
            self::IMAGE => $this->createAttachment($path),
            self::VIDEO => $this->createVideo($path),
        };
    }

    public function createAttachment(string $path): Attachment
    {
        $filename = Str::afterLast($path, '/');
        [$name, $extension] = explode('.', $filename);
        $filesize = Storage::disk('nas-media')->size($path);

        $attachment = Attachment::updateOrCreate([
            'md5' => md5($path . $filesize),
            'original_name' => $filename,
            'size' => $filesize,
        ], [
            'alt_name' => $name,
            'disk' => 'attachments',
            'extension' => $extension,
        ]);

        $attachment->format('thumb');

        return $attachment;
    }

    public function createVideo(string $path): Video
    {
        $filename = Str::afterLast($path, '/');
        [$name, $extension] = explode('.', $filename);
        $filesize = Storage::disk('nas-media')->size($path);

        return Video::updateOrCreate([
            'original_name' => $filename,
            'alt_name' => $name,
            'extension' => $extension,
            'size' => $filesize,
        ]);
    }
}
