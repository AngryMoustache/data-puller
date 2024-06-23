<?php

namespace App\Filters;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class QueryFilter extends Filter
{
    public string $type = 'query';

    public null | int $id = null;

    public null | string $icon = 'heroicon-o-magnifying-glass';

    public string $key;

    public function __construct(public string $value)
    {
        $this->key = $value;
    }

    public function matches(array $pull, Collection $filters): bool
    {
        return Str::contains(
            Str::slug($pull['name']),
            Str::slug($this->value),
            true
        );
    }
}
