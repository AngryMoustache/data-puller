<?php

namespace Api\Entities;

use Api\Entities\Media\Media;
use Api\Jobs\SyncPull;
use App\Models\Artist;
use App\Models\Origin;
use Illuminate\Support\Collection;

class Pullable
{
    public string $name;
    public null | string $originalName = null;
    public string $source;

    public Media | Collection $media;
    public Origin $origin;

    public null | Collection $tags;
    public null | Artist $artist = null;

    public function save(Origin $origin)
    {
        $this->origin = $origin;

        SyncPull::dispatch($this);
    }

    public function tags(): Collection
    {
        return (isset($this->tags) && $this->tags instanceof Collection)
            ? $this->tags
            : collect();
    }
}
