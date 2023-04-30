<?php

namespace App\Entities;

use App\Enums\FilterTypes;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Model;

class Filter
{
    public function __construct(
        public string $type,
        public null | int $id = null,
        public string $value,
        public string $key,
    ) {
        //
    }

    public function matches(array $pull): bool
    {
        return in_array(
            $this->key,
            $pull[FilterTypes::fromClass($this->type)] ?? [],
        );
    }

    public static function fromModel(Model $item)
    {
        $filterClass = match (get_class($item)) {
            Tag::class => HasAllFilter::class,
            default => HasOneFilter::class,
        };

        return new $filterClass(
            get_class($item),
            $item->id,
            $item->long_name ?? $item->name,
            $item->slug ?? $item->id,
        );
    }

    public function toArray()
    {
        return [
            'type' => $this->type,
            'id' => $this->id,
            'value' => $this->value,
            'key' => $this->key,
        ];
    }
}
