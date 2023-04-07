<?php

namespace Api\Jobs;

use Api\Entities\Media\Media;
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
        $pull = Pull::firstOrCreate([
            'source_url' => $this->pull->source
        ], [
            'name' => $this->pull->name,
            'artist_id' => $this->pull->artist->id,
            'origin_id' => $this->pull->origin->id,
        ]);

        // Don't save the media if it was not created now
        if (! $pull->wasRecentlyCreated) {
            return;
        }

        // Save the media
        Collection::wrap($this->pull->media)->each(function (Media $media, $key) use ($pull) {
            SaveMedia::dispatch($media, $pull, $key + 1000);
        });
    }
}
