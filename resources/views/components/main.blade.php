<div class="relative w-screen h-screen overflow-hidden flex gap-4 w-full">
    <x-nav.column />

    <div class="h-screen overflow-y-auto w-full">
        {{ $slot }}
    </div>
</div>
