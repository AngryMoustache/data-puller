<?php

namespace Api\Jobs;

use Api\Entities\Pullable;
use App\Models\Origin;
use App\Models\Pull;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
        $pull = Pull::updateOrCreate(['source_url' => $this->pull->source], [
            'name' => $this->pull->name,
            'origin_id' => $this->pull->origin->id,
        ]);

        // Don't save the media if it was not created now
        if (! $pull->wasRecentlyCreated && ! $pull->attachment) {
            return $pull;
        }

        // Save the media
        $this->pull->media = Collection::wrap($this->pull->media)->map->save();

        // Attach the media to the pull
        $this->pull->media->filter()->each(function ($item) use ($pull) {
            DB::insert(
                'INSERT INTO media_pull (media_type, media_id, pull_id) values (?, ?, ?)',
                [get_class($item), $item->id, $pull->id]
            );
        });

        return $pull;
    }
}
