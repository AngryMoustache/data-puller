@props([
    'open' => false,
    'title' => 'Open me',
])

<div {{ $attributes }} x-data="{ open: @js($open) }">
    <x-headers.h2 class="cursor-pointer" x-on:click="open = !open">
        {{ $title }}
    </x-headers.h2>

    {{ $slot }}
</div>
