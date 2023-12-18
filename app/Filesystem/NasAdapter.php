<?php

namespace App\Filesystem;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Config;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;

class NasAdapter implements FilesystemAdapter
{
    public function fileExists(string $path): bool
    {
        $md5 = md5($path);
        return Cache::rememberForever("nass-exists-{$md5}", function () use ($path) {
            return true;
            return Storage::disk('nas-media')->exists("mobileart/public/attachments/$path");
        });
    }

    public function directoryExists(string $path): bool
    {
        return Cache::rememberForever("nas-directory-exists-{$path}", function () use ($path) {
            return true;
            return Storage::disk('nas-media')->directoryExists($path);
        });
    }

    public function write(string $path, string $contents, Config $config): void
    {

    }

    public function writeStream(string $path, $contents, Config $config): void
    {

    }

    public function read(string $path): string
    {

    }

    public function readStream(string $path)
    {

    }

    public function delete(string $path): void
    {

    }

    public function deleteDirectory(string $path): void
    {

    }

    public function createDirectory(string $path, Config $config): void
    {

    }

    public function setVisibility(string $path, string $visibility): void
    {

    }

    public function visibility(string $path): FileAttributes
    {

    }

    public function mimeType(string $path): FileAttributes
    {

    }

    public function lastModified(string $path): FileAttributes
    {

    }

    public function fileSize(string $path): FileAttributes
    {

    }

    public function listContents(string $path, bool $deep): iterable
    {

    }

    public function move(string $source, string $destination, Config $config): void
    {

    }

    public function copy(string $source, string $destination, Config $config): void
    {

    }
}
