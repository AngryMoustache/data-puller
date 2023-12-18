<?php

namespace App\Models;

use App\Models\Attachment;
use Api\Clients\OpenAI;
use App\Enums;
use App\PullMedia;
use App\Pulls;
use App\ThumbnailFaker;
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
        'thumbnails',
        'views',
        'verdict_at',
    ];

    public $casts = [
        'status' => Enums\Status::class,
        'thumbnails' => 'array',
        'verdict_at' => 'datetime',
    ];

    public $with = [
        'origin',
        'attachments',
        'videos',
        'tagGroups',
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

    public function tagGroups()
    {
        return $this->hasMany(TagGroup::class);
    }

    public function attachments()
    {
        return $this->morphedByMany(Attachment::class, 'media', 'media_pull')
            ->withPivot(['sort_order'])
            ->orderByPivot('sort_order');
    }

    public function videos()
    {
        return $this->morphedByMany(Video::class, 'media', 'media_pull')
            ->withPivot(['sort_order'])
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
        $tags = $this->tagGroups
            ->pluck('tags')
            ->flatten(1)
            ->pluck('slug')
            ->unique()
            ->toArray();

        return Pulls::make()
            ->where('id', '!=', $this->id)
            ->sortByDesc(fn (array $pull) =>
                collect($pull['tags'])
                    ->pluck('tags')
                    ->flatten()
                    ->unique()
                    ->intersect($tags)
                    ->count()
                +
                (int) (collect($pull['artists'])->first() === $this->artist?->slug),
            )
            ->limit($amount)
            ->fetch();
    }

    public function getAttachmentAttribute()
    {
        return $this->mainThumbnail
            ?? $this->attachments->first()
            ?? $this->videos->first()?->preview;
    }

    public function getMainThumbnailAttribute(): null|ThumbnailFaker
    {
        return collect($this->thumbnails)
            ->filter(fn ($i) => !! ($i['is_main'] ?? false))
            ->pluck('thumbnail_url')
            ->mapInto(ThumbnailFaker::class)
            ->first();
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

    public function tagIcons()
    {
        return $this->tagGroups
            ->pluck('tags')
            ->flatten(1)
            ->pluck('icon')
            ->filter()
            ->unique()
            ->toArray();
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
