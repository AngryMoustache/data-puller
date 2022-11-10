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
        $tags = Pull::get()
            ->pluck('tags')
            ->flatten()
            ->unique(fn ($item) => $item->id . $item?->pivot?->data)
            ->values();

        return JsonTag::collection($tags)
            ->groupBy('id')
            ->map(function ($group) {
                if (empty($group->first()?->extra)) {
                    return $group;
                }

                $new = clone $group->first();
                $new->fullSlug = $new->slug;
                $new->extraSlug = '';
                $new->extra = '';

                return $group->prepend($new);
            })
            ->flatten();
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('sorted', function ($query) {
            return $query->orderBy('name');
        });
    }
}
