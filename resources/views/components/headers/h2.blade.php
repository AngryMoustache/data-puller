@props([
    'text' => $slot,
    'textRight' => '',
])

<h2 {{ $attributes->merge([
    'class' => 'font-semibold text-lg flex gap-8 items-center',
]) }}>
    <span>{{ $text }}</span>
    <div class="opacity-50 border-b border-border grow"></div>
    @if (filled($textRight))
        <span>{{ $textRight }}</span>
    @endif
</h2>
