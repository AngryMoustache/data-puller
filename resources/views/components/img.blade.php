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
    style="transition: opacity 0.5s ease-in-out"
    {{ $attributes->except('src') }}
    x-bind:src="loaded ? source : ''"
    x-bind:class="{
        'opacity-0': ! loaded,
        'opacity-100': loaded,
    }"
/>
