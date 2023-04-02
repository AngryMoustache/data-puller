@props([
    'pull',
])

<x-surface {{ $attributes->except('pull')->merge([
    'class' => 'w-full flex flex-col gap-4',
]) }}>
    <div class="overflow-hidden rounded flex items-center" style="aspect-ratio: 3/2.5">
        <x-img class="w-full" src="{{ $pull->attachment?->format('thumb') }}" />
    </div>

    <div class="flex flex-col gap-1">
        <a href="{{ $pull->route() }}" class="font-bold line-clamp-1">
            {{ $pull->name }}
        </a>

        <div class="flex justify-between opacity-75">
            <span>
                {{ $pull->views }}
                {{ Str::plural('view', $pull->views) }}
            </span>

            <span>
                {{ optional($pull->pulledWhen)->diffForHumans() }}
            </span>
        </div>
    </div>
</x-surface>
