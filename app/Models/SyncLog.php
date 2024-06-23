<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyncLog extends Model
{
    protected $fillable = [
        'origin_id',
        'message',
        'meta',
        'handled',
    ];

    protected $casts = [
        'meta' => 'array',
        'handled' => 'boolean',
    ];

    public function origin(): BelongsTo
    {
        return $this->belongsTo(Origin::class);
    }

    public static function booted(): void
    {
        static::addGlobalScope('handled', fn ($query) => $query->where('handled', false));
        static::addGlobalScope('ordered', fn ($query) => $query->latest());
    }
}
