<?php

namespace App\Livewire\Wireables;

use App\Filters\Filter;
use App\Filters\HasAllFilter;
use App\Filters\HasOneFilter;
use App\Filters\QueryFilter;
use App\Enums\FilterTypes;
use App\Enums\MediaType;
use App\Enums\Sorting;
use App\Models\Origin;
use App\Pulls;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Wireable;

class FilterBag implements Wireable
{
    public Collection $filters;

    public Sorting $sort = Sorting::NEWEST;

    public MediaType $mediaType = MediaType::ALL;

    public null | int $randomizerSeed = null;

    public function __construct(array|string $filterString)
    {
        if (is_string($filterString)) {
            $filters = explode('/', $filterString);
        }

        $filters = collect($filterString)
            ->map(fn ($filter) => explode(':', $filter))
            ->filter(fn ($filter) => count($filter) > 1)
            ->mapWithKeys(fn ($filter) => [$filter[0] => explode(',', $filter[1])]);

        // Get filters that come from models
        $this->filters = $filters
            ->filter(fn ($i, $type) => $type !== 'query' && Str::contains(FilterTypes::fromString($type), 'Model'))
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

        // Is there a media type?
        if (isset($filters['media-type'])) {
            $this->mediaType = MediaType::tryFrom($filters['media-type'][0]) ?? MediaType::ALL;
        }
    }

    public function toPulls(): Pulls
    {
        // Set the randomizer seed
        srand($this->randomizerSeed);

        // Loop over the filters and filter/sort them
        return Pulls::make()
            // Filter out prompts when not filtering on origins
            ->withPrompts($this->getOrigins()->count() > 0)
            ->when($this->sort, fn ($items) => $this->sort->sortCollection($items))
            ->when($this->mediaType, fn ($items) => $this->mediaType->filter($items))
            ->filter(function (array $pull) {
                $filters = $this->filters->groupBy('type');

                return $filters->every(function (Collection $items) use ($pull) {
                    $check = match(get_class($items->first())) {
                        HasAllFilter::class => 'every',
                        HasOneFilter::class, QueryFilter::class => 'first',
                    };

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

    public function setMediaType(string $mediaType)
    {
        $this->mediaType = is_string($mediaType) ? MediaType::from($mediaType) : $mediaType;
    }

    public function setOrigins(array $origins)
    {
        // First remove all origins
        $this->resetOrigins();

        foreach ($origins as $origin) {
            $origin = Origin::whereSlug($origin)->first();

            $this->filters->push(Filter::fromModel($origin));
        }
    }

    public function resetOrigins()
    {
        $this->filters = $this->filters->reject(fn (Filter $filter) =>
            $filter->type === FilterTypes::ORIGIN->value
        );
    }

    public function getOrigins(): Collection
    {
        return $this->filters->where(fn (Filter $filter) => $filter->type === FilterTypes::ORIGIN->value);
    }

    public function toggleFilter(string $type, $id)
    {
        if ($type === 'query') {
            $this->filters = $this->filters
                ->reject(fn (Filter $filter) => $filter->type === $type)
                ->when($id, fn ($items) => $items->push(new QueryFilter($id)));

            return;
        }

        $filter = $this->filters->firstWhere(fn (Filter $filter) => $filter->type === $type && $filter->id === $id);

        if ($filter) {
            $this->filters = $this->filters->reject(fn (Filter $filter) => $filter->type === $type && $filter->id === $id);
        } else {
            $this->filters->push(Filter::fromModel($type::find($id)));
        }
    }

    public function buildQueryString(null|string $extra = null, bool $implode = true)
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
            ->when(
                $this->mediaType !== MediaType::ALL,
                fn ($items) => $items->push("media-type:{$this->mediaType->value}")
            )
            ->when(filled($extra), fn ($items) => $items->push($extra))
            ->when($implode, fn ($items) => $items->implode('/'));
    }

    public function toLivewire()
    {
        return $this->buildQueryString(implode: false);
    }

    public static function fromLivewire($value)
    {
        return new static($value);
    }
}
