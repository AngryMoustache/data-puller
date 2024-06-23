<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JapaneseTranslation extends Model
{
    protected $fillable = [
        'pull_id',
        'media_id',
        'translation',
        'original',
        'location',
    ];

    protected $casts = [
        'location' => 'array',
    ];

    public function pull()
    {
        return $this->belongsTo(Pull::class);
    }

    public function media()
    {
        return $this->belongsTo(Attachment::class);
    }

    public function kanji()
    {
        return $this->belongsToMany(Kanji::class);
    }
}
