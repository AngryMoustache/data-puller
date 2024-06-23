<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RatingCategory extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'slug',
    ];

    public function pulls()
    {
        return $this->belongsToMany(Pull::class, 'ratings')
            ->withTimestamps()
            ->withPivot('rating');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public static function booted()
    {
        static::saving(function ($category) {
            $category->slug = Str::slug($category->name);
        });

        static::addGlobalScope('sorted', function ($query) {
            $query->orderBy('name');
        });
    }
}
