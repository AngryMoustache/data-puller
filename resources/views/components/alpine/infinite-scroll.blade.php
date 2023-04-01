<div
    {{ $attributes}}
    x-on:scroll.window="checkLoad"
    x-data="{
        loading: false,
        checkLoad () {
            const el = ($root.offsetHeight + $root.offsetTop) - 200
            const scroll = window.innerHeight + window.scrollY

            if (scroll >= el && !this.loading) {
                $wire.loadMore()
                this.loading = true
                setTimeout(() => this.loading = false, 1000)
            }
        }
    }"
>
    {{ $slot }}
</div>
