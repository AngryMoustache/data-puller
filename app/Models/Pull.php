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
        'artist',
        'source_url',
        'status',
        'preview_id',
        'comic',
        'views',
        'verdict_at',
    ];

    public $casts = [
        'status' => Enums\Status::class,
        'comic' => 'boolean',
        'verdict_at' => 'datetime',
    ];

    public $with = [
        'preview',
        'attachments',
    ];

    public function origin()
    {
        return $this->belongsTo(Origin::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function preview()
    {
        return $this->belongsTo(Attachment::class);
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
        return $this->attachments->first() ?? $this->videos->first()->preview;
    }

    public function getPulledWhenAttribute()
    {
        return ($this->verdict_at ?? $this->created_at)->isoFormat('lll');
    }

    public function getFormattedViewsAttribute()
    {
        return number_format($this->views);
    }

    public function getGridSizeAttribute()
    {
        $ratioX = round($this->image->width / $this->image->height);
        $ratioY = round($this->image->height / $this->image->width);

        return collect((object) [
            'columns' => ($ratioX > $ratioY) ? 2 : 1,
            'rows' => ($ratioX < $ratioY) ? 2 : 1,
        ]);
    }

    public function getRelatedAttribute()
    {
        return self::where('id', '!=', $this->id)
            ->with('tags')
            ->online()
            ->get()
            ->sortByDesc(fn ($pull) => $pull->tags->intersect($this->tags)->count())
            ->take(100);
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
