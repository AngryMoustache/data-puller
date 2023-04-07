@props([
    'src' => null,
    'width' => 1,
    'height' => 1,
])

<img
    x-data="{
        loaded: false,
        source: @js($src),
        init () {
            {{-- Lazyload the source and set the src when it's loaded --}}
            const img = new Image()
            img.src = this.source
            img.onload = () => {
                this.loaded = true
            }
        }
    }"

    x-bind:src="loaded ? source : 'images/pixel.png'"

    x-bind:class="{
        '!opacity-100': loaded,
        'animate animate-pulse rounded': ! loaded,
    }"

    x-bind:style="{
        'aspect-ratio': ! loaded ? '{{ $width }} / {{ $height }}' : 'auto'
    }"

    {{ $attributes->except('src')->merge([
        'class' => 'w-full opacity-0 bg-border transition-all',
    ]) }}
/>
