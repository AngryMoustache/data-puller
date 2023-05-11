@props([
    'prompt',
    'pull' => $prompt->pull,
])

<a
    href="{{ $prompt->route() }}"
    {{ $attributes->except('prompt')->merge([
        'class' => 'w-full flex gap-1 md:gap-4 flex-col md:flex-row',
    ]) }}
>
    <div class="w-full md:w-64 overflow-hidden rounded" style="aspect-ratio: 3/2.5">
        @if ($pull)
            <x-img
                src="{{ $pull->attachment?->format('thumb') }}"
                :width="3"
                :height="2.5"
            />
        @else
            <div
                class="flex w-full items-center justify-center bg-surface"
                style="aspect-ratio: 3/2.5"
            >
                <x-heroicon-o-question-mark-circle class="opacity-50 w-12 h-12" />
            </div>
        @endif
    </div>

    <div class="w-full flex flex-col gap-1 md:gap-2 py-2 overflow-hidden">
        <div class="flex items-center justify-between">
            <span class=" line-clamp-1">
                <span class="font-bold">
                    {{ $prompt->name }}
                </span>

                <span class="text-dark ml-2">
                    #{{ $prompt->id }}
                </span>
            </span>
        </div>

        <div class="opacity-75">
            {{ $prompt->date->isoFormat('MMM Do, YYYY') }}
        </div>

        <div class="w-full md:line-clamp-2 hidden">
            {{ $prompt->description }}
        </div>
    </div>
</a>
