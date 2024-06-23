@props([
    'enabled' => true,
])

<div
    {{ $attributes}}
    @if ($enabled)
        x-on:scroll.window="checkLoad"
        x-data="{
            loading: false,
            checkLoad () {
                const el = ($root.offsetHeight + $root.offsetTop) - 200
                const scroll = window.innerHeight + window.scrollY

                if (scroll >= el && !this.loading) {
                    $wire.loadMore()
                    this.loading = true
                    setTimeout(() => this.loading = false, 500)
                }
            }
        }"
    @endif
>
    {{ $slot }}
</div>
