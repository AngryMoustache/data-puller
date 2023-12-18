<?php

namespace App\Filters;

use App\Enums\FilterTypes;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Filter
{
    public function __construct(
        public string $type,
        public null | int $id = null,
        public string $value,
        public string $key,
        public null | string $icon = null,
    ) {
        //
    }

    public function matches(array $pull, Collection $filters): bool
    {
        return in_array(
            $this->key,
            $pull[FilterTypes::fromClass($this->type)] ?? [],
        );
    }

    public static function fromModel(Model $item)
    {
        $filterClass = match (get_class($item)) {
            Tag::class => HasGroupedTagsFilter::class,
            default => HasOneFilter::class,
        };

        $icon = match (get_class($item)) {
            'query' => 'heroicon-o-magnifying-glass',
            \App\Models\Artist::class => 'heroicon-o-user-group',
            \App\Models\Folder::class => 'heroicon-o-folder-open',
            \App\Models\Origin::class => 'heroicon-o-rss',
            \App\Models\Tag::class => $item->icon ?? 'heroicon-o-tag',
            default => 'heroicon-o-tag',
        };

        return new $filterClass(
            get_class($item),
            $item->id,
            $item->long_name ?? $item->name,
            $item->slug ?? $item->id,
            $icon,
        );
    }

    public function toArray()
    {
        return [
            'type' => $this->type,
            'id' => $this->id,
            'value' => $this->value,
            'key' => $this->key,
            'icon' => $this->icon,
        ];
    }
}
