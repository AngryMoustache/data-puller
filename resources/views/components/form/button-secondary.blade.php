<a tabindex="0" {{ $attributes->merge([
    'class' => '
        inline-block px-10 py-3 rounded text-lg font-bold cursor-pointer
        transition-all hover:scale-105
    ',
]) }}>
    @if (isset($label))
        {{ $label }}
    @else
        {{ $slot }}
    @endif
</a>
