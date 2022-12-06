@if ($stopped)
    <x-headers.h3
        text="You have reached the end!"
        class="w-full text-center opacity-50 font-normal italic"
    />

    <script wire:key="infinite-stopped"> window.onscroll = undefined </script>
@else
    <script wire:key="infinite-running" wire:loading.remove>
        window.setTimeout(() => window.onscroll = function(ev) {
            if (
                ! window.timeout &&
                document.body.offsetHeight > 750 &&
                (window.innerHeight + window.scrollY) >= document.body.offsetHeight
            ) {
                window.timeout = true
                @this.addPage()
                window.setTimeout(() => window.timeout = false, 500)
            }
        }, 1000)
    </script>
@endif
