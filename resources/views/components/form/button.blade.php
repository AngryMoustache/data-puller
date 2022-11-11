<a {{ $attributes->merge([
    'class' => '
        inline-block px-10 py-3 bg-gradient-dark rounded-xl text-lg font-bold
        transition-all hover:scale-105
    ',
]) }}>
    @if (isset($label))
        {{ $label }}
    @else
        {{ $slot }}
    @endif
</a>
