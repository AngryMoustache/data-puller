@props([
    'pull',
])

<a
    href="{{ $pull->route() }}"
    {{ $attributes->except('pull')->merge([
        'class' => 'w-full flex gap-1 md:gap-4 flex-col md:flex-row',
    ]) }}
>
    <div class="w-full md:w-64 overflow-hidden rounded" style="aspect-ratio: 3/2.5">
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

        <div class="w-full md:line-clamp-2 hidden md:block">
            {{ $pull->tags->where('hidden', 0)->pluck('name')->join(', ') }}
        </div>
    </div>
</a>
