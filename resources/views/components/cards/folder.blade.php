@props([
    'folder',
])

<a
    href="{{ $folder->route() }}"
    {{ $attributes->except('folder')->merge([
        'class' => 'bg-surface rounded p-4 p-2 w-full flex flex-col gap-2',
    ]) }}
>
    <div class="overflow-hidden rounded flex items-center" style="aspect-ratio: 3/2.5">
        <x-img
            src="{{ $folder->attachment?->format('thumb') }}"
            width="3"
            height="2.5"
        />
    </div>

    <div class="flex flex-col p-1">
        <span class="font-bold line-clamp-1">
            {{ $folder->name }}
        </span>

        <div class="text-sm opacity-75">
            {{ optional($folder->created_at)->diffForHumans() }}
        </div>
    </div>
</a>
