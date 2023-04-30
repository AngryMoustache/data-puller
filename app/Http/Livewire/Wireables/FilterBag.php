<?php

namespace App\Http\Livewire\Wireables;

use App\Entities\Filter;
use App\Entities\HasAllFilter;
use App\Entities\HasOneFilter;
use App\Entities\QueryFilter;
use App\Enums\FilterTypes;
use App\Enums\Sorting;
use App\Models\Origin;
use App\Pulls;
use Illuminate\Support\Collection;
use Livewire\Wireable;

class FilterBag implements Wireable
{
    public Collection $filters;

    public Sorting $sort = Sorting::NEWEST;

    public null | int $randomizerSeed = null;

    public function __construct(string $filterString)
    {
        $filters = collect(explode('/', $filterString))
            ->map(fn ($filter) => explode(':', $filter))
            ->filter(fn ($filter) => count($filter) > 1)
            ->mapWithKeys(fn ($filter) => [$filter[0] => explode(',', $filter[1])]);

        // Get filters that come from models
        $this->filters = $filters
            ->filter(fn ($i, $type) => $type !== 'query' && FilterTypes::fromString($type))
            ->map(fn ($items, $type) => FilterTypes::fromString($type)::whereIn('slug', $items)->get())
            ->flatten()
            ->map(fn ($item) => Filter::fromModel($item));

        // Is there a search term?
        if (isset($filters['query'])) {
            $this->filters->push(new QueryFilter($filters['query'][0]));
        }

        // Is randomized?
        if (isset($filters['randomizer'])) {
            $this->randomizerSeed = $filters['randomizer'][0];
            $this->sort = Sorting::RANDOM;
        }

        // Is being sorted?
        if (isset($filters['sort'])) {
            $this->sort = Sorting::tryFrom($filters['sort'][0]) ?? Sorting::NEWEST;
        }
    }

    public function toPulls()
    {
        // Set the randomizer seed
        srand($this->randomizerSeed);

        // Loop over the filters and filter/sort them
        return Pulls::make()
            ->when($this->sort, fn ($items) => $this->sort->sortCollection($items))
            ->filter(function (array $pull) {
                $filters = $this->filters->groupBy('type');

                return $filters->every(function (Collection $items) use ($pull) {
                    $check = match(get_class($items->first())) {
                        QueryFilter::class => 'query',
                        HasAllFilter::class => 'every',
                        HasOneFilter::class => 'first',
                    };

                    if ($check === 'query') {
                        return $items->first()->matches($pull);
                    }

                    return $items->{$check}->matches($pull);
                });
            });
    }

    public function setSorting(string | Sorting $sort)
    {
        $this->sort = is_string($sort) ? Sorting::from($sort) : $sort;

        if ($this->randomizerSeed === null && $this->sort->isRandomizer()) {
            $this->randomizerSeed = now()->timestamp;
        }
    }

    public function setOrigin(string $origin)
    {
        // Unset the existing origin if there is one
        $this->filters = $this->filters->reject(fn (Filter $filter) => $filter->type === FilterTypes::ORIGIN->value);
        if (! empty($origin)) {
            $this->filters->push(Filter::fromModel(Origin::whereSlug($origin)->first()));
        }
    }

    public function getOrigin(): null | Filter
    {
        return $this->filters->firstWhere(fn (Filter $filter) => $filter->type === FilterTypes::ORIGIN->value);
    }

    public function toggleFilter(string $type, null | int $id)
    {
        if ($type === 'query') {
            $this->filters = $this->filters
                ->reject(fn (Filter $filter) => $filter->type === $type)
                ->push(new QueryFilter($id));

            return;
        }

        $filter = $this->filters->firstWhere(fn (Filter $filter) => $filter->type === $type && $filter->id === $id);

        if ($filter) {
            $this->filters = $this->filters->reject(fn (Filter $filter) => $filter->type === $type && $filter->id === $id);
        } else {
            $this->filters->push(Filter::fromModel($type::find($id)));
        }
    }

    public function buildQueryString()
    {
        return $this->filters
            ->groupBy('type')
            ->map(function (Collection $items, string $class) {
                $key = FilterTypes::fromClass($class);
                $value = $items->pluck('key')->implode(',');

                return "{$key}:{$value}";
            })
            ->when(
                $this->randomizerSeed !== null,
                fn ($items) => $items->push("randomizer:{$this->randomizerSeed}")
            )
            ->when(
                ! in_array($this->sort, [Sorting::RANDOM, Sorting::NEWEST]),
                fn ($items) => $items->push("sort:{$this->sort->value}")
            )
            ->implode('/');
    }

    public function toLivewire()
    {
        return $this->buildQueryString();
    }

    public static function fromLivewire($value)
    {
        return new static($value);
    }
}
