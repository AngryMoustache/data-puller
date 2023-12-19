@props([
    'open' => false,
    'title' => 'Open me',
])

<div x-data="{ open: @js($open) }" {{ $attributes->except(['x-ref'])->merge(['class' => '
    border border-border rounded-xl
']) }}>
    <div
        {{ $attributes->only(['x-ref']) }}
        x-on:click="open = !open"
        x-bind:class="{ 'border-b': open }"
        class="w-full border-border cursor-pointer"
    >
        <div
            x-bind:class="{ 'rounded-b-none': open }"
            class="
                px-4 py-2 flex flex-row items-center gap-2
                hover:bg-surface overflow-hidden rounded-xl
            "
        >
            <x-heroicon-o-chevron-down
                class="w-6 h-6 transition-all"
                x-bind:class="{ 'transform rotate-180': open }"
            />

            <x-headers.h3>
                {{ $title }}
            </x-headers.h3>
        </div>
    </div>

    <div class="p-4" x-bind:class="{ '!p-0 h-0 overflow-hidden': ! open }">
        {{ $slot }}
    </div>
</div>
