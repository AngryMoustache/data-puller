<?php

namespace App\Models;

use AngryMoustache\Media\Models\Attachment;
use Api\Clients\OpenAI;
use App\Enums;
use App\PullMedia;
use App\Pulls;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Pull extends Model
{
    protected $fillable = [
        'origin_id',
        'name',
        'slug',
        'artist_id',
        'source_url',
        'status',
        'views',
        'verdict_at',
    ];

    public $casts = [
        'status' => Enums\Status::class,
        'verdict_at' => 'datetime',
    ];

    public $with = [
        'origin',
        'attachments',
        'videos',
    ];

    public function origin()
    {
        return $this->belongsTo(Origin::class);
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function folders()
    {
        return $this->belongsToMany(Folder::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function attachments()
    {
        return $this->morphedByMany(Attachment::class, 'media', 'media_pull')
            ->withPivot(['sort_order', 'is_thumbnail'])
            ->orderByPivot('sort_order');
    }

    public function videos()
    {
        return $this->morphedByMany(Video::class, 'media', 'media_pull')
            ->withPivot(['sort_order', 'is_thumbnail'])
            ->orderByPivot('sort_order');
    }

    public function ratings()
    {
        return $this->belongsToMany(RatingCategory::class, 'category_rating_pull')
            ->withPivot('rating');
    }

    public function route()
    {
        return route('pull.show', $this->slug);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function related(int $amount = 12)
    {
        $tags = $this->tags->pluck('slug')->toArray();

        return Pulls::make()
            ->where('id', '!=', $this->id)
            ->sortByDesc(fn (array $pull) =>
                max(
                    collect($pull['tags'])->intersect($tags)->count() - $pull['views'],
                    0
                )
            )
            ->limit($amount)
            ->fetch();
    }

    public function getAttachmentAttribute()
    {
        return $this->attachments()->where('is_thumbnail', 1)->first()
            ?? $this->attachments->first()
            ?? $this->videos->first()?->preview;
    }

    public function getMediaAttribute()
    {
        return $this->videos->merge($this->attachments)
            ->sortBy('pivot.sort_order')
            ->values()
            ->mapInto(PullMedia::class);
    }

    public function getPulledWhenAttribute()
    {
        return $this->verdict_at ?? $this->created_at;
    }

    public function getListInfoAttribute()
    {
        return collect([
            $this->artist?->name,
            $this->views . ' ' . Str::plural('view', $this->views)
        ])->filter()->join(' - ');
    }

    public function scopePending($query)
    {
        return $query->where('status', Enums\Status::PENDING)->where(function ($query) {
            $query->whereHas('attachments')->orWhereHas('videos');
        });
    }

    public function scopeOnline($query)
    {
        return $query->where('status', Enums\Status::ONLINE);
    }

    public function scopeOffline($query)
    {
        return $query->where('status', Enums\Status::OFFLINE);
    }

    public function canHaveSourceUrl()
    {
        if (! $this->origin || ! $this->origin->type) {
            return false;
        }

        return $this->origin->type !== Enums\Origin::EXTERNAL;
    }

    public static function getAiName(Collection $tags): string | null
    {
        return OpenAI::getNameBasedOnTags($tags);
    }
}
