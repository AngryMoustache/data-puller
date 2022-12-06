<h5 {{ $attributes->merge([
    'class' => 'opacity-50',
]) }}>
    @if (isset($text))
        {{ $text }}
    @else
        {{ $slot }}
    @endif
</h5>
