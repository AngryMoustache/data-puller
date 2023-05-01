<?php

namespace App\Models;

use AngryMoustache\Media\Models\Attachment;
use App\Enums;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI;

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
            ->withPivot('sort_order')
            ->orderByPivot('sort_order');
    }

    public function videos()
    {
        return $this->morphedByMany(Video::class, 'media', 'media_pull');
    }

    public function route()
    {
        return route('pull.show', $this->slug);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getAttachmentAttribute()
    {
        return $this->attachments->first()
            ?? $this->videos->first()?->preview;
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
        return $query->where('status', Status::PENDING);
    }

    public function scopeOnline($query)
    {
        return $query->where('status', Status::ONLINE);
    }

    public function scopeOffline($query)
    {
        return $query->where('status', Status::OFFLINE);
    }

    public static function getAiName(Collection $tags): string | null
    {
        $tags = $tags->pluck('long_name')->join(', ');

        $result = OpenAI::completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => config('openai.prompt_start') . $tags . '.',
            'max_tokens' => 150,
            'temperature' => 0.7,
            'top_p' => 1,
        ]);

        return Str::of($result['choices'][0]['text'] ?? '')
            ->replace('"', '')
            ->trim()
            ->__toString();
    }
}
