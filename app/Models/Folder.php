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
        return $this->belongsToMany(Pull::class);
    }


    public static function booted()
    {
        static::saving(function (self $folder) {
            $folder->slug = Str::slug($folder->name);
        });
    }
}
