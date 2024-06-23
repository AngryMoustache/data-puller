@props([
    'filters',
    'slideshow' => null,
])

@if ($slideshow || $filters->filters->isNotEmpty())
    <div {{ $attributes->only('class')->merge([
        'class' => 'md:flex justify-between gap-4',
    ]) }}>
        <div class="w-full md:w-fit flex flex-wrap gap-4">
            @foreach ($filters->filters as $filter)
                <x-tag
                    wire:key="enabled-tag-{{ $filter->key }}"
                    class="flex gap-3 !py-2 !px-4 !text-base"
                    wire:click="toggleFilter({{ json_encode($filter->type) }}, {{ json_encode($filter->id) }})"
                >
                    <x-dynamic-component
                        :component="$filter->icon"
                        class="w-5 h-5"
                    />

                    {{ $filter->value }}
                </x-tag>
            @endforeach
        </div>
    </div>
@endif
