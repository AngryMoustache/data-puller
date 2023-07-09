<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RatingCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'online',
    ];

    public $casts = [
        'online' => 'boolean',
    ];

    public function pulls()
    {
        return $this->belongsToMany(Pull::class)
            ->withPivot('rating');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public static function booted()
    {
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });

        static::addGlobalScope('online', function ($query) {
            $query->where('online', true);
        });
    }
}
