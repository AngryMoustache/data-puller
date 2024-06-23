<?php

namespace App\Models;

use Api\Jobs\RebuildTagNames;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'long_name',
        'icon',
        'is_hidden',
        'is_highlighted',
    ];

    public $casts = [
        'is_hidden' => 'boolean',
        'is_highlighted' => 'boolean',
    ];

    public function tagGroups()
    {
        return $this->belongsToMany(TagGroup::class);
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

    public static function checkOrphans(iterable $tags)
    {
        $tags = self::with('parent.parent.parent')->find($tags);

        return $tags->filter(function (Tag $tag) use ($tags) {
            $check = $tag;
            while ($check?->parent) {
                $check = $check->parent;

                if (! $tags->pluck('id')->contains($check->id)) {
                    return false;
                }
            }

            return true;
        })->pluck('id');
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('sorted', function ($query) {
            $query->orderBy('name');
        });

        static::saving(function (self $tag) {
            $tag->long_name = $tag->generateLongName();
            $tag->slug = Str::slug($tag->long_name);

            if ($tag->isDirty('name')) {
                RebuildTagNames::dispatch();
            }
        });
    }
}
