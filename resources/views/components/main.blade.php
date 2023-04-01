<div
    class="relative flex w-full"
    x-data="{
        open: false,
        toggle() { this.open = ! this.open }
    }"
>
    <x-nav.column />

    <div
        class="h-screen w-full transition-all ml-24"
        x-bind:class="{'ml-64': open}"
    >
        <x-nav.header />

        {{ $slot }}
    </div>
</div>
