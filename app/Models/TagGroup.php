<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagGroup extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
}
