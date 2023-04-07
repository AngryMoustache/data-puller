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

    class="w-full opacity-0 bg-border"
    style="transition: opacity 0.5s ease-in-out"

    {{ $attributes->except('src') }}
/>
