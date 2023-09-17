<?php

namespace App\Filters;

use App\Enums\FilterTypes;
use Illuminate\Support\Collection;

class HasGroupedTagsFilter extends Filter
{
    public function matches(array $pull, Collection $filters): bool
    {
        $groups = Collection::wrap(
            $pull[FilterTypes::fromClass($this->type)] ?? []
        );

        return $groups
            ->reject(fn ($group) => $filters->pluck('key')->diff($group['tags'])->isNotEmpty())
            ->isNotEmpty();
    }
}
