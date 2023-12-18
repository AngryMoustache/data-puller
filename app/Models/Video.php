<?php

namespace App\Models;

use App\Filesystem\MediaServer;
use App\Models\Attachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Video extends Model
{
    protected $fillable = [
        'original_name',
        'alt_name',
        'preview_id',
        'extension',
        'size',
    ];

    public $with = [
        'preview',
    ];

    public function preview()
    {
        return $this->belongsTo(Attachment::class);
    }

    public function fullPath()
    {
        return MediaServer::url($this->uuid);
    }

    public function path()
    {
        return MediaServer::url($this->uuid);
    }

    public static function booted()
    {
        static::creating(function ($video) {
            $video->uuid = Str::uuid();
        });
    }
}
