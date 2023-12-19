<?php

namespace App\Models;

use Api\Clients\OpenAI;
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

    public function children()
    {
        return $this->hasMany(Artist::class, 'parent_id');
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

        if (mb_detect_encoding($name) !== 'ASCII') {
            $name = OpenAI::translateToEnglish($name);
        }

        $artist = Artist::firstOrCreate([
            'name' => $name,
            'slug' => Str::slug($name),
        ]);

        while ($artist->parent) {
            $artist = $artist->parent;
        }

        return $artist;
    }
}
