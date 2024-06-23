<?php

namespace Api\Jobs;

use Api\Entities\Media\Media;
use Api\Entities\Pullable;
use App\Models\Pull;
use App\Models\Tag;
use App\Models\TagGroup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class SyncPull implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Pullable $pull)
    {
        //
    }

    public function handle()
    {
        // Create or update the pull
        $pull = Pull::firstOrCreate([
            'source_url' => $this->pull->source
        ], [
            'name' => $this->pull->name,
            'original_name' => $this->pull->originalName,
            'artist_id' => $this->pull->artist?->id,
            'origin_id' => $this->pull->origin?->id,
        ]);

        // Don't save the media if it was not created now
        if (! $pull->wasRecentlyCreated) {
            return;
        }

        // Save the media
        Collection::wrap($this->pull->media)->each(function (Media $media, $key) use ($pull) {
            SaveMedia::dispatch($media, $pull, $key + 1000);
        });

        // Create the automatic tag group
        $tags = $this->pull->tags()->map->fetch()->filter();

        $tagGroup = TagGroup::updateOrCreate([
            'pull_id' => $pull->id,
            'name' => 'Main tags',
            'is_main' => true,
        ]);

        $tags->each(function (Tag $tag) use ($tagGroup) {
            $tagList = [$tag->id];
            while ($tag->parent) {
                $tagList[] = $tag->parent->id;
                $tag = $tag->parent;
            }

            $tagGroup->tags()->attach($tagList);
        });
    }
}
