<?php

namespace App\Models\Pivot;

use App\Models\Pull;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MediaPull extends Pivot
{
    protected $table = 'media_pull';

    protected $fillable = [
        'pull_id',
        'media_type',
        'media_id',
        'sort_order',
    ];

    public function pull()
    {
        return $this->belongsTo(Pull::class);
    }

    public function media()
    {
        return $this->morphTo();
    }
}
