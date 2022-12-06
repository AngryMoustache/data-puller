<h4 {{ $attributes->merge([
    'class' => 'font-bold text-2xl',
]) }}>
    @if (isset($text))
        {{ $text }}
    @else
        {{ $slot }}
    @endif
</h4>
