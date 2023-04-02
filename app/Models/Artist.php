<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo(Artist::class);
    }

    public function pulls()
    {
        return $this->hasMany(Pull::class);
    }

    public function route()
    {
        return route('pull.index', "artist:{$this->slug}");
    }
}
