<?php

namespace App\Filters;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class QueryFilter extends Filter
{
    public string $type = 'query';

    public null | int $id = null;

    public string $key;

    public function __construct(public string $value)
    {
        $this->key = Str::slug($value);
    }

    public function matches(array $pull, Collection $filters): bool
    {
        return Str::contains(
            Str::slug($pull['name']),
            $this->value,
            true
        );
    }
}
