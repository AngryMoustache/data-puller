<?php

namespace Api\Entities;

use Api\Entities\Media\Media;
use Api\Jobs\SyncPull;
use App\Models\Origin;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Pullable
{
    public string $name;
    public string $source;
    public string $artist = '';

    public Media|Collection $media;
    public Origin $origin;

    public function save(Origin $origin)
    {
        $this->origin = $origin;

        SyncPull::dispatch($this);
    }

    public function checkJapanese($value, $fallback = null)
    {
        if (empty($value) || mb_detect_encoding($value) !== 'ASCII') {
            $value = $fallback ?? rand(10000, 99999);
        }

        return Str::of($value)->trim();
    }
}
