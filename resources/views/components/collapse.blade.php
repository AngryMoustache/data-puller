@props([
    'open' => false,
    'title' => '',
])

<div class="border rounded-lg overflow-hidden" x-data="{ open: @js($open) }">
    <div
        x-on:click="open = ! open"
        {{ $attributes->only('class')->merge([
            'class' => '
                flex px-4 py-2 justify-between items-center
                font-medium text-gray-700 bg-gray-100
                cursor-pointer hover:bg-gray-200
            ',
        ]) }}
    >
        {{ $title }}

        <i @if ($open) style="display: none" @endif x-show="open" class="fas fa-chevron-down"></i>
        <i @if (! $open) style="display: none" @endif x-show="! open" class="fas fa-chevron-up"></i>
    </div>

    <div style="display: none" x-show="open">
        {{ $slot }}
    </div>
</div>
