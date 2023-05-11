<?php

namespace Api\Entities;

use Api\Clients\OpenAI;
use Api\Entities\Media\Media;
use Api\Jobs\SyncPull;
use App\Models\Artist;
use App\Models\Origin;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Pullable
{
    public string $name;
    public string $source;

    public Media|Collection $media;
    public Origin $origin;

    public null | Artist $artist = null;

    public function save(Origin $origin)
    {
        $this->origin = $origin;

        SyncPull::dispatch($this);
    }

    public function checkJapanese($value, $fallback = null)
    {
        if (empty($value) || mb_detect_encoding($value) !== 'ASCII') {
            $value = OpenAI::translateToEnglish($value);
        }

        if (empty($value)) {
            $value = $fallback;
        }

        return Str::of($value)->trim();
    }
}
