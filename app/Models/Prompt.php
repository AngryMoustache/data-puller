<?php

namespace App\Models;

use Api\Clients\OpenAI;
use App\Pulls;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Prompt extends Model
{
    protected $fillable = [
        'date',
        'description',
        'pull_id',
    ];

    public $casts = [
        'date' => 'date',
    ];

    public function getRouteKeyName()
    {
        return 'date';
    }

    public function pull()
    {
        return $this->belongsTo(Pull::class);
    }

    public function attachments()
    {
        return $this->belongsToMany(Attachment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)
            ->orderBy('tags.long_name');
    }

    public function getGroupedTagsAttribute()
    {
        return $this->tags
            ->reject(fn ($tag) => $tag->is_hidden)
            ->groupBy(fn ($tag) => explode(':', $tag->long_name)[0] ?? null);
    }

    public function relatedPulls(int $amount = 3)
    {
        return Pulls::make()
            ->reject(fn (array $pull) => $pull['origins'] === ['prompts'])
            ->sortByDesc(fn (array $pull) => collect($pull['tags'])
                ->intersect($this->tags->pluck('slug'))
                ->count()
            )
            ->take($amount)
            ->fetch();
    }

    public function route()
    {
        return route('prompt.show', $this->date->format('Y-m-d'));
    }

    public function generateNameDescription()
    {
        $this->name = OpenAI::getNameBasedOnTags($this->tags);
        $this->description = OpenAI::getPromptBasedOnTags($this->tags);

        $this->saveQuietly();
    }

    public static function getDay(null | Carbon $day = null): static
    {
        $prompt = static::firstOrCreate([
            'date' => ($day ?? now())->format('Y-m-d'),
        ]);

        if ($prompt->tags->isEmpty()) {
            $prompt->tags()->sync(static::getTagList()->pluck('id'));
        }

        $prompt = $prompt->refresh();

        $prompt->generateNameDescription();

        return $prompt;
    }

    // Doing this hard-coded for now, don't judge
    public static function getTagList(): Collection
    {
        $tags = collect();

        // Accessories (13)
        while (rand(0, 2) === 0) {
            $tags->push(Tag::where('slug', 'like', 'accessories-%')->get()->random());
        }

        // Clothing (2)
        $tags->push(Tag::where('slug', 'like', 'clothing-top-%')->get()->random());
        $tags->push(Tag::where('slug', 'like', 'clothing-legs-%')->get()->random());
        $tags->push(Tag::where('slug', 'like', 'clothing-feet-%')->get()->random());

        while (rand(0, 2) === 0) {
            $tags->push(Tag::where('slug', 'like', 'clothing-gloves%')->get()->random());
        }

        // Hair (3)
        $tags->push(
            Tag::where('slug', 'like', 'hair-%')
                ->whereNot('slug', 'like', 'hair-color-%')
                ->get()
                ->random()
        );

        $tags->push(Tag::where('slug', 'like', 'hair-color-%')->get()->random());

        // Situation (4)
        $situationGroup = Tag::where('id', collect([
            // Weighed by the amount of occurrences
            20, 20, 20, 20, 20,
            67, 67, 67, 67, 67, 67, 67,
            159,
            201,
            240,
        ])->random())->first()->slug;

        $items = Tag::where('slug', 'like', "{$situationGroup}-%")->get();
        $tags->push($items->random(rand(1, $items->count() < 3 ? $items->count() : 3)));

        // Extra situations
        while (rand(0, 2) === 0) {
            $situationGroup = Tag::where('id', collect([
                // Weighed by the amount of occurrences
                206,
                189,
                370,
                56, 56,
                442,
                207,
            ])->random())->first()->slug;

            $tags->push(Tag::where('slug', 'like', "{$situationGroup}%")->get()->random());
        }

        // Mouth (24)
        $situationGroup = Tag::find(24)->slug;
        $exludedGroup = Tag::find(65)->slug;

        $items = Tag::where('slug', 'like', "{$situationGroup}-%")
            ->whereNot('slug', 'like', "{$exludedGroup}-%")
            ->get();

        $tags->push($items->random());
        $items = Tag::where('slug', 'like', "{$exludedGroup}-%")->get();
        $tags->push($items->random());

        // Other (5)
        while (rand(0, 3) === 0) {
            $situationGroup = Tag::where('id', collect([
                // Weighed by the amount of occurrences
                446,
                88,
                93,
                89,
            ])->random())->first()->slug;

            $tags->push(Tag::where('slug', 'like', "{$situationGroup}%")->get()->random());
        }

        return $tags->flatten()->unique('id');
    }
}
