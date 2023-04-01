@props([
    'pull',
])

<div {{ $attributes->except('pull')->merge([
    'class' => 'w-full flex gap-4',
]) }}>
    <div class="w-64 overflow-hidden rounded" style="aspect-ratio: 3/2.5">
        <x-img class="w-full" src="{{ $pull->attachment->format('thumb') }}" />
    </div>

    <div class="w-full flex flex-col gap-2 py-2">
        <div class="flex items-center justify-between">
            <a href="{{ $pull->route() }}" class="font-bold line-clamp-1">
                {{ $pull->name }}
            </a>
        </div>

        <div class="opacity-75">
            {{ $pull->listInfo }}
        </div>

        <div class="w-full line-clamp-2">
            {{ $pull->tags->where('hidden', 0)->pluck('name')->join(', ') }}
        </div>
    </div>
</div>
