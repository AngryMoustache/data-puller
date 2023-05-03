@props([
    'pull',
])

<a
    href="{{ $pull->route() }}"
    {{ $attributes->except('pull')->merge([
        'class' => 'bg-surface rounded p-4 pb-3 w-full flex flex-col gap-2',
    ]) }}
>
    <div class="overflow-hidden rounded flex items-center" style="aspect-ratio: 3/2.5">
        <x-img
            src="{{ $pull->attachment?->format('thumb') }}"
            width="3"
            height="2.5"
        />
    </div>

    <div class="flex flex-col p-1">
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
