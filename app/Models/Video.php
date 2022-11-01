<?php

namespace App\Models;

use AngryMoustache\Media\Models\Attachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    protected $fillable = [
        'name',
        'filename',
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

    public function path()
    {
        return Storage::path("public/videos/{$this->id}/{$this->original_name}");
    }
}
