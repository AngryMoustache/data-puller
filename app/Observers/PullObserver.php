<?php

namespace App\Observers;

use Api\Jobs\RebuildCache;
use App\Models\Pull;
use Illuminate\Support\Str;

class PullObserver
{
    public function saving(Pull $pull)
    {
        $slug = Str::slug($pull->name);
        $pull->slug = $this->createUniqueSlug($slug, $pull->id);

        RebuildCache::dispatch();
    }

    private function createUniqueSlug($slug, $id)
    {
        $i = 1;
        $originalSlug = $slug;
        while ($this->otherRecordExistsWithSlug($slug, $id) || $slug === '') {
            $slug = $originalSlug . '-' . $i++;
        }

        return $slug;
    }

    protected function otherRecordExistsWithSlug($slug, $id)
    {
        return Pull::where('slug', $slug)
            ->where('id', '!=', $id)
            ->withoutGlobalScopes()
            ->exists();
    }
}
