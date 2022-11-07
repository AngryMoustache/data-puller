<?php

namespace App\Models;

use App\Resources\JsonTag;
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

    public static function fullTagList()
    {
        return self::with('pulls')->get()->map(function ($tag) {
            return $tag->pulls->pluck('pivot.data')
                ->map(fn ($i) => json_decode($i))
                ->flatten()
                ->unique()
                ->map(function ($extra) use ($tag) {
                    $json = JsonTag::single($tag);
                    $json->extra = $extra;

                    return $json;
                })
                ->prepend(JsonTag::single($tag));
        })->flatten(1);
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('sorted', function ($query) {
            return $query->orderBy('name');
        });
    }
}
