<div>
    <x-header>
        <x-headers.h1>Welcome back!</x-headers.h1>
    </x-header>

    <x-container class="p-4">
        <x-card>
            {{-- TEMP --}} <x-detail.pull :pull="$pulls->random()" />
            {{-- <x-detail.pull :pull="$latest" /> --}}
        </x-card>
    </x-container>

    <x-container class="pt-0 p-4">
        <x-card>
            <x-grids.pulls :$pulls />
        </x-card>
    </x-container>
</div>
