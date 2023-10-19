<?php

namespace App\Models;

use AngryMoustache\Media\Models\Attachment as ModelsAttachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Image\Image;

class Attachment extends ModelsAttachment
{
    protected $fillable = [
        'md5',
        'original_name',
        'alt_name',
        'disk',
        'height',
        'width',
        'size',
        'mime_type',
        'extension',
        'folder_location',
        'crops',
    ];

    public function format($format)
    {
        $path = $this->getPath($format);

        // Don't allow gifs, mp4s, ...
        if (in_array(Str::afterLast($path, '.'), config('media.ignore-extensions'))) {
            return $this->path();
        }

        // It's there, we just need to go get it
        if (Storage::disk('attachments')->exists($path)) {
            return $this->getUrl($path);
        }

        try {
            // It doesn't exist, create it
            $formatClass = $this->guessFormatClass($format);
            $tmpPath = "tmp--{$this->original_name}";

            $file = file_get_contents($this->path());
            file_put_contents($tmpPath, $file);

            $image = Image::load($tmpPath);
            $formatClass::render($image);
            $image->save($tmpPath);

            // Upload it to the NAS
            $formatName = $this->id . '/' . ($format ? $format . '-' : '') . $this->original_name;
            Storage::disk('nas-media')->putFileAs('mobileart/public/attachments/',  $tmpPath, $formatName);

            // Remove the tmp file
            unlink($tmpPath);

            return $this->getUrl($formatName) . ($formatClass::$alwaysRefresh ? '?r=' . rand(1, 1000) : '');
        } catch (\Throwable $th) {
            report($th);

            return $this->path();
        }
    }

    public function getUrl(string $path)
    {
        return "https://media.mobileart.dev/public/attachments/{$path}";
    }

    public function path()
    {
        return $this->getUrl("{$this->id}/{$this->original_name}");
    }

    public function guessFormatClass(string $format)
    {
        $formatClassApp = 'App\\Formats\\' . ucfirst($format);
        $formatClass = 'AngryMoustache\\Media\\Formats\\' . ucfirst($format);

        if (class_exists($formatClassApp)) {
            return $formatClassApp;
        } elseif (class_exists($formatClass)) {
            return $formatClass;
        } else {
            throw new Exception('Image Format not found, please create one in App\\Formats');
        }
    }
}
