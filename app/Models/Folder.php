<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public $with = [
        'pulls',
    ];

    public function pulls()
    {
        return $this->belongsToMany(Pull::class);
    }

    public function route()
    {
        return route('folders.show', $this->slug);
    }

    public function getImageAttribute()
    {
        return $this->pulls->last()?->image;
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
    }
}
