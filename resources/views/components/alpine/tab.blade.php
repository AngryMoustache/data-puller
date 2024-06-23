@props([
    'tabKey' => [],
])

<div
    {{ $attributes->merge(['class' => 'p-4']) }}
    x-show="activeTab === '{{ $tabKey }}'"
>
    {{ $slot }}
</div>
