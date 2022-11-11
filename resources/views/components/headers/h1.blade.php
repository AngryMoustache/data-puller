<h1 {{ $attributes->merge([
    'class' => 'font-bold text-3xl',
]) }}>
    @if (isset($text))
        {{ $text }}
    @else
        {{ $slot }}
    @endif
</h1>
