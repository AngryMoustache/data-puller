<?php

namespace App\Models;

use AngryMoustache\Media\Models\Attachment;
use App\Enums;
use App\Enums\Status;
use App\Pulls;
use Illuminate\Database\Eloquent\Model;
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

    public function getViewsAttribute($value)
    {
        return number_format($value);
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
}
