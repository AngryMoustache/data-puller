@props([
    'pull',
    'count' => $pull->media->count(),
])

<a
    href="{{ $pull->route() }}"
    {{ $attributes->except('pull')->merge([
        'class' => 'w-full flex gap-1 md:gap-4 flex-col md:flex-row',
    ]) }}
>
    <div class="relative w-full md:w-64 overflow-hidden rounded" style="aspect-ratio: 3/2.5">
        <div class="absolute top-1 right-1 text-xs flex gap-1">
            @foreach ($pull->tagIcons() as $icon)
                <span class="bg-black bg-opacity-75 rounded px-2 py-1">
                    <x-dynamic-component
                        :component="$icon"
                        class="w-4 h-4 text-text"
                    />
                </span>
            @endforeach

            @if ($count > 1)
                <span class="font-bold bg-black bg-opacity-75 rounded px-2 py-1">
                    {{ $count }}
                </span>
            @endif
        </div>

        <x-img
            src="{{ $pull->attachment?->format('thumb') }}"
            :width="3"
            :height="2.5"
        />
    </div>

    <div class="w-full flex flex-col gap-1 md:gap-2 py-2 overflow-hidden">
        <div class="flex items-center justify-between">
            <span class=" line-clamp-1">
                <span class="font-bold">
                    {{ $pull->name }}
                </span>

                <span class="text-dark ml-2">
                    #{{ $pull->id }}
                </span>
            </span>
        </div>

        <div class="opacity-75">
            {{ $pull->listInfo }}
        </div>

        <div class="w-full md:line-clamp-2 hidden">
            {{ $pull->tagGroups->pluck('tags')->flatten(1)->where('is_hidden', 0)->pluck('name')->join(', ') }}
        </div>
    </div>
</a>
