<strong class="text-primary">
    @if (isset($text))
        {{ $text }}
    @else
        {{ $slot }}
    @endif
</strong>
