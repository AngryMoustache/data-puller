<?php

namespace App\Livewire\Wireables;

use App\Enums\SortDir;
use App\Enums\Sorting;
use Illuminate\Support\Collection;
use Livewire\Wireable;

class SortingBag implements Wireable
{
    public Collection $filters;

    public Sorting $column = Sorting::VERDICT;
    public SortDir $direction = SortDir::DESC;
    public null | string $category;

    public function __construct(array $filters = [])
    {
        $this->category = $filters['category'] ?? null;
        $this->column = Sorting::tryFrom($filters['column'] ?? null) ?? Sorting::VERDICT;
        $this->direction = SortDir::tryFrom($filters['direction'] ?? null) ?? SortDir::DESC;
    }

    public function sortCollection(Collection $items): Collection
    {
        return $this->column->sortCollection($items, $this->direction, [
            'category' => $this->category,
        ]);
    }

    public function hasQueryString(): bool
    {
        return $this->column !== Sorting::RANDOM
            && ! ($this->column === Sorting::default() && $this->direction === SortDir::default());
    }

    public function buildQueryString(): string
    {
        $string = collect([
            $this->column->value,
            $this->direction->value,
            $this->column->hasCategory() ? $this->category : null,
        ])
            ->filter()
            ->implode(',');

        return "sort:{$string}";
    }

    public function column(Sorting $column): void
    {
        $this->column = $column;
    }

    public function direction(SortDir $direction): void
    {
        $this->direction = $direction;
    }

    public function category(string $category): void
    {
        $this->category = $category;
    }

    public function toLivewire()
    {
        return [
            'column' => $this->column->value,
            'direction' => $this->direction->value,
            'category' => $this->category,
        ];
    }

    public static function fromLivewire($value)
    {
        return new static($value);
    }
}
