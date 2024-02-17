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

    public function toJavascript()
    {
        return [
            'id' => $this->id,
            'pull_id' => $this->pull_id,
            'name' => $this->name,
            'is_main' => $this->is_main,
            'tags' => $this->tags->pluck('id')->mapWithKeys(fn (int $id) => [$id => true])->toArray(),
        ];
    }

    public static function booted()
    {
        static::saving(function (self $group) {
            $group->slug = Str::slug($group->name);
        });
    }
}
