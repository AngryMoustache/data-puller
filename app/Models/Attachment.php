<?php

namespace App\Models;

use AngryMoustache\Media\Models\Attachment as ModelsAttachment;
use App\Filesystem\MediaServer;
use Illuminate\Support\Str;

class Attachment extends ModelsAttachment
{
    protected $fillable = [
        'uuid',
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

    public function translations()
    {
        return $this->hasMany(JapaneseTranslation::class, 'media_id');
    }

    public static function livewireUpload($file)
    {
        if (! is_file($file->getRealPath())) {
            return null;
        }

        return MediaServer::uploadFromLivewire($file);
    }

    public function path()
    {
        return MediaServer::url($this->uuid);
    }

    public function format($format)
    {
        if (blank($this->uuid)) {
            return '';
        }

        return MediaServer::format($this->uuid, $format);
    }

    public static function booted()
    {
        static::creating(function ($attachment) {
            $attachment->uuid = Str::uuid();
        });
    }
}
