@props([
    'open' => false,
    'title' => 'Open me',
])

<div x-data="{ open: @js($open) }" {{ $attributes->merge(['class' => '
    border border-border rounded-xl overflow-hidden
']) }}>
    <div
        x-on:click="open = !open"
        x-bind:class="{ 'border-b': open }"
        class="
            w-full border-border px-4 py-2 flex flex-row items-center gap-2
            hover:bg-surface cursor-pointer
        "
    >
        <x-heroicon-o-chevron-down
            class="w-6 h-6 transition-all"
            x-bind:class="{ 'transform rotate-180': open }"
        />

        <x-headers.h3 :text="$title" />
    </div>

    <div class="p-4" x-show="open">
        {{ $slot }}
    </div>
</div>
