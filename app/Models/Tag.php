<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'long_name',
        'hidden',
    ];

    public function pulls()
    {
        return $this->belongsToMany(Pull::class);
    }

    public function parent()
    {
        return $this->belongsTo(Tag::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Tag::class, 'parent_id');
    }

    public function route()
    {
        return route('pull.index', [
            'filterString' => "tags:{$this->slug}",
        ]);
    }

    public function getNameWithCountAttribute()
    {
        if ($this->children->isNotEmpty()) {
            return "{$this->name} ({$this->children->count()})";
        }

        return $this->name;
    }

    public function generateLongName()
    {
        $longName = $this->name;
        $parent = $this->parent;

        while ($parent) {
            $longName = "{$parent->name} : {$longName}";
            $parent = $parent->parent;
        }

        return $longName;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('sorted', function ($query) {
            $query->orderBy('name');
        });

        static::saving(function ($tag) {
            $tag->long_name = $tag->generateLongName();
            $tag->slug = Str::slug($tag->long_name);
        });
    }
}
