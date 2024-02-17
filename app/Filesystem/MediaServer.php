<?php

namespace App\Filesystem;

use App\Models\Attachment;
use App\Models\Video;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MediaServer
{
    public static $baseUrl = 'https://media.mobileart.dev';
    public static $project = '62cea60c-0acf-4e34-8e21-003e86db36d4';

    public static function upload(string $file, string $uuid, string $filename, string $disk = 'attachments'): ?string
    {
        $response = Http::attach('file', $file, $filename)->post(self::$baseUrl . '/api/upload', [
            'project_id' => self::$project,
            'uuid' => $uuid,
            'disk' => $disk,
        ]);

        if ($response->failed()) {
            return null;
        }

        return $response->collect()[0] ?? null;
    }

    public static function uploadFromUrl(string $url, string $uuid, string $filename, string $disk = 'attachments')
    {
        $response = Http::post(self::$baseUrl . '/api/upload/url', [
            'project_id' => self::$project,
            'uuid' => $uuid,
            'url' => $url,
            'filename' => $filename,
            'disk' => $disk,
        ]);

        if ($response->failed()) {
            return null;
        }

        return $response->collect()[0] ?? null;
    }

    public static function uploadFromLivewire(TemporaryUploadedFile $file): null | Attachment | Video
    {
        $original = $file->getClientOriginalName();
        $fileInfo = getimagesize($file->getRealPath());

        $media = match (Str::afterLast($original, '.')) {
            'mp4' => Video::withoutGlobalScopes()->firstOrCreate([
                'original_name' => $original,
                'alt_name' => $original,
                'extension' => Str::afterLast($original, '.'),
                'size' => filesize($file->getRealPath()),
            ]),
            default => Attachment::withoutGlobalScopes()->firstOrCreate([
                'original_name' => $original,
                'alt_name' => $original,
                'md5' => md5_file($file->getRealPath()),
                'disk' => config('media.default-disk', 'public'),
                'width' => $fileInfo[0],
                'height' => $fileInfo[1],
                'mime_type' => $fileInfo['mime'],
                'size' => filesize($file->getRealPath()),
                'extension' => $file->guessExtension(),
            ]),
        };

        $response = Http::attach('file', file_get_contents($file->getRealPath()), $original)
            ->post(self::$baseUrl . '/api/upload', [
                'project_id' => self::$project,
                'uuid' => $media->uuid,
                'disk' => match ($media::class) {
                    Video::class => 'videos',
                    default => 'attachments',
                },
            ]);

        if ($response->failed()) {
            return null;
        }

        return $media;
    }

    public static function uploadFormat(Attachment $attachment, string $formatName, string $base64): ?string
    {
        $response = Http::attach('file', base64_decode($base64), "{$formatName}-{$attachment->name}")
            ->post(self::$baseUrl . '/api/upload/format', [
                'project_id' => self::$project,
                'uuid' => $attachment->uuid,
                'formatName' => $formatName,
            ]);

        if ($response->failed()) {
            return null;
        }

        return $response->collect()[0] ?? null;
    }

    public static function format(string $uuid, string $format): string
    {
        return self::$baseUrl . '/' . self::$project . "/{$uuid}/{$format}";
    }

    public static function url(string $uuid): string
    {
        return self::$baseUrl . '/' . self::$project . "/{$uuid}";
    }
}
