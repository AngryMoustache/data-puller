<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function pulls()
    {
        return $this->belongsToMany(Pull::class)
            ->withPivot('data');
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('sorted', function ($query) {
            return $query->orderBy('name');
        });
    }
}
