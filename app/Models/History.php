<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    public $timestamps = false;

    protected $table = 'history';

    protected $fillable = [
        'pull_id',
        'viewed_on',
        'last_viewed_at',
    ];

    protected $casts = [
        'viewed_on' => 'date',
        'last_viewed_at' => 'datetime',
    ];

    public function pull()
    {
        return $this->belongsTo(Pull::class);
    }

    public static function add(Pull $pull): self
    {
        $pull->increment('views');

        return self::updateOrCreate([
            'pull_id' => $pull->id,
            'viewed_on' => now()->format('Y-m-d'),
        ], [
            'last_viewed_at' => now(),
        ]);
    }

    public static function getList()
    {
        return self::with('pull.tags')
            ->orderBy('last_viewed_at', 'desc')
            ->where('viewed_on', '>=', now()->subDays(7))
            ->get()
            ->groupBy('viewed_on')
            ->mapWithKeys(fn ($group) => [
                $group->first()->viewed_on->format('l, F jS') => $group->pluck('pull')
            ]);
    }
}
