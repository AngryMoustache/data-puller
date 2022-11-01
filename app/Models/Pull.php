<?php

namespace App\Models;

use AngryMoustache\Media\Models\Attachment;
use App\Enums;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;

class Pull extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'source_url',
        'origin',
        'status',
        'views',
        'verdict_at',
    ];

    public $casts = [
        'origin' => Enums\Origin::class,
        'status' => Enums\Status::class,
        'verdict_at' => 'datetime',
    ];

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

    public function getImageAttribute()
    {
        return $this->attachments->first()
            ?? $this->videos->first()->preview;
    }

    public function getRelatedAttribute()
    {
        return $this->tags->pluck('pulls')->flatten()->unique()->where('id', '!=', $this->id);
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
