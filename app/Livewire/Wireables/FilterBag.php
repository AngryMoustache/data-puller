<?php

namespace App\Livewire\Wireables;

use App\Filters\Filter;
use App\Filters\HasGroupedTagsFilter;
use App\Filters\HasOneFilter;
use App\Filters\QueryFilter;
use App\Enums\FilterTypes;
use App\Enums\MediaType;
use App\Enums\Sorting;
use App\Models\Artist;
use App\Models\Origin;
use App\Models\RatingCategory;
use App\Pulls;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Wireable;

class FilterBag implements Wireable
{
    public Collection $filters;

    public SortingBag $sorting;

    public MediaType $mediaType = MediaType::ALL;

    public null | int $randomizerSeed = null;

    public function __construct(array|string $filters = '')
    {
        if (is_string($filters)) {
            $filters = explode('/', $filters);
        }

        $filters = collect($filters)
            ->map(fn ($filter) => explode(':', $filter))
            ->filter(fn ($filter) => count($filter) > 1)
            ->mapWithKeys(fn ($filter) => [$filter[0] => explode(',', $filter[1])]);

        // Get filters that come from models
        $this->filters = $filters
            ->filter(fn ($i, $type) => $type !== 'query' && Str::contains(FilterTypes::fromString($type), 'Model'))
            ->map(function ($items, $type) {
                $model = FilterTypes::fromString($type);

                return $model::whereIn('slug', $items)
                    ->when($model === Artist::class, fn ($query) => $query->whereNull('parent_id'))
                    ->get();
            })
            ->flatten()
            ->map(fn ($item) => Filter::fromModel($item));

        // Is there a search term?
        if (isset($filters['query'])) {
            $this->filters->push(new QueryFilter($filters['query'][0]));
        }

        // Is being sorted?
        $this->sorting = new SortingBag([
            'column' => $filters['sort'][0] ?? null,
            'direction' => $filters['sort'][1] ?? null,
            'category' => $filters['sort'][2] ?? RatingCategory::pluck('slug')->first(),
        ]);

        // Is randomized?
        if (isset($filters['randomizer'])) {
            $this->randomizerSeed = $filters['randomizer'][0];
            $this->sorting->column(Sorting::RANDOM);
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
            ->when($this->sorting, fn ($items) => $this->sorting->sortCollection($items))
            ->when($this->mediaType, fn ($items) => $this->mediaType->filter($items))
            ->filter(function (array $pull) {
                $filters = $this->filters->groupBy('type');

                return $filters->every(function (Collection $items) use ($pull) {
                    $check = match(get_class($items->first())) {
                        HasGroupedTagsFilter::class => 'every',
                        HasOneFilter::class, QueryFilter::class => 'first',
                    };

                    return $items->{$check}->matches($pull, $items);
                });
            });
    }

    public function setSorting(array $sorting)
    {
        $this->sorting = new SortingBag($sorting);
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
                $value = $items->pluck('key')->unique()->implode(',');

                return "{$key}:{$value}";
            })
            ->when(
                $this->randomizerSeed !== null,
                fn ($items) => $items->push("randomizer:{$this->randomizerSeed}")
            )
            ->when(
                $this->sorting->hasQueryString(),
                fn ($items) => $items->push($this->sorting->buildQueryString())
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
