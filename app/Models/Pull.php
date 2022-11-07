<?php

namespace App\Models;

use AngryMoustache\Media\Models\Attachment;
use App\Enums;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;

class Pull extends Model
{
    protected $fillable = [
        'origin_id',
        'name',
        'slug',
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
        'attachments',
    ];

    public function origin()
    {
        return $this->belongsTo(Origin::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)
            ->withPivot('data');
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

    public function getSuggestedTagsAttribute()
    {
        $similar = collect(explode(' ', $this->name))
            ->map(fn ($part) => ['soundex', 'LIKE', soundex($part) . '%'])
            ->filter();

        return Tag::where(function ($query) use ($similar) {
            $similar->each(fn ($i) => $query->orWhere(...$i));
        })->get();
    }

    public function getJsonAttachmentsAttribute()
    {
        return $this->attachments->map(fn ($attachment) => [
            'id' => $attachment->id,
            'path' => $attachment->format('thumb')
        ])->toArray();
    }

    public function getImageAttribute()
    {
        return $this->attachments->first() ?? $this->videos->first()->preview;
    }

    public function getPulledWhenAttribute()
    {
        return ($this->verdict_at ?? $this->created_at)->isoFormat('lll');
    }

    public function scopePending($query)
    {
        return $query->where('status', Status::PENDING);
    }

    public function scopeOnline($query)
    {
        return $query->where('status', Status::ONLINE);
    }
}
