<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        return route('pull.index', "artists:{$this->slug}");
    }

    public static function guess(null|string $name): null|Artist
    {
        if (empty($name)) {
            return null;
        }

        $artist = Artist::firstOrCreate([
            'name' => $name,
            'slug' => Str::slug($name),
        ]);

        return $artist->parent ?? $artist;
    }
}
