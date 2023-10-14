@props([
    'pull',
    'image' => $pull->thumbnail ?? $pull->attachment->format('thumb'),
    'count' => $pull->media->count(),
])

<a
    href="{{ $pull->route() }}"
    {{ $attributes->except('pull')->merge([
        'class' => 'bg-surface rounded p-2 pb-3 w-full flex flex-col gap-2',
    ]) }}
>
    <div class="relative overflow-hidden rounded flex items-center" style="aspect-ratio: 3/2.5">
        <div class="absolute top-1 right-1 text-xs flex gap-1">
            @if ($count > 1)
                <span class="bg-black bg-opacity-75 rounded px-2 py-1">
                    {{ $count }} <i class="text-xs fa-solid fa-layer-group ml-1"></i>
                </span>
            @endif

            @if ($pull->hasVideo())
                <span class="bg-black bg-opacity-75 rounded px-2 py-1">
                    <x-heroicon-s-play class="w-4 h-4 text-text" />
                </span>
            @endif
        </div>

        <x-img
            wire:key="pull-{{ $pull->id }}--{{ $image }}"
            src="{{ $image }}"
            width="3"
            height="2.5"
        />
    </div>

    <div class="flex flex-col px-2 py-1">
        <div class="flex justify-between gap-1">
            <span class="font-bold line-clamp-1">
                {{ $pull->name }}
            </span>

            <span class="text-dark">
                #{{ $pull->id }}
            </span>
        </div>

        <div class="text-sm flex justify-between opacity-75">
            <span>

                {{ optional($pull->pulledWhen)->diffForHumans() }}
            </span>

            <span>
                {{ $pull->views }}
                {{ Str::plural('view', $pull->views) }}
            </span>
        </div>
    </div>
</a>
