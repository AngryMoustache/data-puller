<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TagGroup extends Model
{
    protected $fillable = [
        'pull_id',
        'name',
        'slug',
        'is_main',
    ];

    public $with = [
        'pull',
        'tags',
    ];

    public $casts = [
        'is_main' => 'boolean',
    ];

    public function pull()
    {
        return $this->belongsTo(Pull::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public static function booted()
    {
        static::saving(function (self $group) {
            $group->slug = Str::slug($group->name);
        });
    }
}
