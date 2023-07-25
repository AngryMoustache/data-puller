<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Folder extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function pulls()
    {
        return $this->belongsToMany(Pull::class)
            ->orderBy('folder_pull.id', 'desc');
    }

    public function getAttachmentAttribute()
    {
        return $this->pulls->first()?->attachment;
    }

    public function route()
    {
        return route('pull.index', [
            'filterString' => "folders:{$this->slug}",
        ]);
    }

    public static function booted()
    {
        static::saving(function (self $folder) {
            $folder->slug = Str::slug($folder->name);
        });
    }
}
