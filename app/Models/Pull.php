<?php

namespace App\Models;

use App\Models\Attachment;
use Api\Clients\OpenAI;
use Api\Clients\OpenRouter;
use App\Enums;
use App\Enums\OpenRouterTarget;
use App\Facades\AI;
use App\PullMedia;
use App\Pulls;
use App\ThumbnailFaker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class Pull extends Model
{
    protected $fillable = [
        'origin_id',
        'name',
        'original_name',
        'slug',
        'artist_id',
        'story',
        'source_url',
        'status',
        'thumbnails',
        'views',
        'verdict_at',
    ];

    public $casts = [
        'status' => Enums\Status::class,
        'story' => 'json',
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
        return $this->belongsToMany(RatingCategory::class, 'ratings')
            ->withTimestamps()
            ->withPivot('rating');
    }

    public function translations()
    {
        return $this->hasMany(JapaneseTranslation::class);
    }

    public function route()
    {
        return route('pull.show', $this->slug);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function generateStory(): string
    {
        $tags = $this->tagGroups->pluck('tags')->flatten()->pluck('name')->join(', ');
        $images = collect([
                $this->videos->first()?->preview,
                ...$this->attachments->map->path(),
            ])
            ->filter()
            ->map(function ($url) {
                try {
                    $image = Image::make($url);
                    $image->resize(560, 560, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });

                    return $image->encode('data-url')->encoded;
                } catch (\Throwable $th) {
                    return null;
                }
            })
            ->filter();

        $prompt = "Write a short story about the given images with a small title and a bad ending, about the following themes: {$tags}. Leave out any NSFW terms and don't add any mysterious figures or characters other than the ones in the image. Don't mention dimly lit rooms.";

        $story = AI::completion(
            target: OpenRouterTarget::GPT,
            data: [
                'max_tokens' => 500,
                'messages' => [['role' => 'user', 'content' => [
                    ['type' => 'text', 'text' => $prompt],
                    ...$images->map(fn ($image) =>
                        ['type' => 'image_url', 'image_url' => ['url' => $image]],
                    ),
                ]]],
            ]
        );

        return Str::replace('Title: ', '', $story);
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

    public function scopeSimpleSearch($query, string $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('id', $search);
        });
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
