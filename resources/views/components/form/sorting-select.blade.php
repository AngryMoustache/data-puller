@props([
    'options',
    'label' => null,
    'categories' => collect(),
    'sortOptions' => [
        \App\Enums\SortDir::ASC,
        \App\Enums\SortDir::DESC,
    ],
])

<div class="flex gap-2" x-bind="{
    'wire:key': sort.direction + '-sorting',
}">
    <div class="flex h-12">
        <div
            class="bg-surface flex rounded-lg w-12"
            x-on:click="sort.direction = (sort.direction === 'asc' ? 'desc' : 'asc')"
            x-show="sort.column !== '{{ \App\Enums\Sorting::RANDOM->value }}'"
            x-transition
        >
            @foreach ($sortOptions as $option)
                <div @class([
                    'grow w-4 items-center justify-center flex cursor-pointer',
                    '-mr-6' => $loop->first,
                ])>
                    <x-dynamic-component
                        :component="$option->icon()"
                        class="w-6 h-6"
                        x-bind:class="{
                            'text-primary': sort.direction === '{{ $option->value }}',
                            'opacity-25': sort.direction !== '{{ $option->value }}',
                        }"
                    />
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex flex-col gap-2 w-full">
        <x-form.select
            :options="$options"
            x-model="sort.column"
        />

        @if ($categories->isNotEmpty())
            <div x-show="sort.column === '{{ Sorting::RATING_CATEGORY->value }}'">
                <x-form.radio-list
                    class="w-full"
                    :options="$categories"
                    x-model="sort.category"
                />
            </div>
        @endif
    </div>
</div>
