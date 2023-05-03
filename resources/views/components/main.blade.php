<div
    class="
        relative flex w-full
        flex-col md:flex-row
    "
    x-data="{
        open: false,
        toggle() { this.open = ! this.open }
    }"
>
    <x-nav.column />

    <div
        class="h-screen w-full transition-all md:ml-24"
        x-bind:class="{'md:ml-64': open, 'md:ml-24': ! open}"
    >
        <x-nav.header />

        {{ $slot }}
    </div>
</div>
