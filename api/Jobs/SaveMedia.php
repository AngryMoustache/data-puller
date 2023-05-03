<?php

namespace Api\Jobs;

use Api\Entities\Media\Media;
use App\Models\Pull;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SaveMedia implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Media $media,
        public Pull $pull,
        public int $sortOrder = 1000,
    ) {
        //
    }

    public function handle()
    {
        $media = $this->media->save();

        DB::insert('INSERT INTO media_pull (media_type, media_id, pull_id, sort_order) values (?, ?, ?, ?)', [
            get_class($media),
            $media->id,
            $this->pull->id,
            $this->sortOrder,
        ]);
    }
}
