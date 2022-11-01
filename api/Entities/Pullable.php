<?php

namespace Api\Entities;

use App\Enums\Origin;
use App\Models\Origin as ModelsOrigin;
use App\Models\Pull;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Pullable
{
    public string $name;
    public string $source;

    public Media|Collection $media;

    public function save(ModelsOrigin $origin)
    {
        // Create or update the pull
        $pull = Pull::updateOrCreate(['source_url' => $this->source], [
            'name' => $this->name,
            'origin' => $origin->type,
            'origin_id' => $origin->id,
        ]);

        // Don't save the media if it was not created now
        if (! $pull->wasRecentlyCreated && ! $pull->attachment) {
            return $pull;
        }

        // Save the media
        $this->media = Collection::wrap($this->media)->map->save();

        // Attach the media to the pull
        $this->media->filter()->each(function ($item) use ($pull) {
            DB::insert(
                'INSERT INTO media_pull (media_type, media_id, pull_id) values (?, ?, ?)',
                [get_class($item), $item->id, $pull->id]
            );
        });

        return $pull;
    }
}
