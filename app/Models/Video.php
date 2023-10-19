<?php

namespace App\Models;

use App\Models\Attachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        return Storage::disk('nas-media')
            ->path("public/videos/{$this->id}/{$this->original_name}");
    }

    public function path()
    {
        return env('NAS_MEDIA_HOST') . "/public/videos/{$this->id}/{$this->original_name}";
    }
}
