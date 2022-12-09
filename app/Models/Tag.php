<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'tag_group_id',
        'name',
        'slug',
    ];

    public function pulls()
    {
        return $this->belongsToMany(Pull::class)
            ->withPivot('data');
    }

    public function tagGroup()
    {
        return $this->belongsTo(TagGroup::class);
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('sorted', function ($query) {
            return $query->orderBy('name');
        });
    }
}
