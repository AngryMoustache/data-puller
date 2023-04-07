@props([
    'pull',
])

<a
    href="{{ $pull->route() }}"
    {{ $attributes->except('pull')->merge([
        'class' => 'bg-surface rounded p-4 p-2 w-full flex flex-col gap-2',
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
        <span class="line-clamp-1">
            <span class="text-dark mr-1">
                #{{ $pull->id }}
            </span>

            <span class="font-bold">
                {{ $pull->name }}
            </span>
        </span>

        <div class="text-sm flex justify-between opacity-75">
            <span>
                {{ $pull->views }}
                {{ Str::plural('view', $pull->views) }}
            </span>

            <span>
                {{ optional($pull->pulledWhen)->diffForHumans() }}
            </span>
        </div>
    </div>
</a>
