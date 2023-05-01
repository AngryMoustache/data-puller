<?php

namespace App\Models;

use App\Pulls;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Prompt extends Model
{
    protected $fillable = [
        'date',
        'discord_pinged',
    ];

    public $casts = [
        'date' => 'date',
        'discord_pinged' => 'integer',
    ];

    public function attachments()
    {
        return $this->belongsToMany(Attachment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)
            ->orderBy('tags.long_name');
    }

    public static function day(null | Carbon $day = null): static
    {
        $prompt = static::firstOrCreate([
            'date' => ($day ?? now())->format('Y-m-d'),
        ]);

        if ($prompt->tags->isEmpty()) {
            $prompt->tags()->sync(static::getTagList()->pluck('id'));
        }

        return $prompt->refresh();
    }

    public function getGroupedTagsAttribute()
    {
        return $this->tags->groupBy(fn ($tag) => explode(' : ', $tag->long_name)[0] ?? null);
    }

    public function relatedPulls(int $amount = 3)
    {
        return Pulls::make()
            ->sortByDesc(fn (array $pull) => collect($pull['tags'])
                ->intersect($this->tags->pluck('slug'))
                ->count()
            )
            ->take($amount)
            ->fetch();
    }

    public static function getTagList(): Collection
    {
        $parents = Tag::whereDoesntHave('parent')
            ->whereNotIn('id', [1, 5])
            ->whereHas('children')
            ->get();

        // Get different amount of children based on the parent
        return $parents
            ->map(fn ($parent) => $parent->children->random(match ($parent->id) {
                2 => 5, // Clothes
                3 => 1, // Hair
                13 => rand(0, 1), // Accessories
                24 => rand(0, 1), // Others
                default => 3,
            }))
            ->flatten()
            ->map(function ($tag) {
                // Check nested children, with a 50% chance of getting a child
                while (($tag->children->isNotEmpty() && rand(0, 1)) || $tag->is_hidden) {
                    $tag = $tag->children->random();
                }

                return $tag;
            })
            ->flatten()
            ->reject(fn ($tag) => $tag->is_hidden);
    }
}
