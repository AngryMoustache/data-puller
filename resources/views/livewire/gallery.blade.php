<div>
    <x-header>
        <x-headers.h1>Welcome back!</x-headers.h1>

        {{-- <ul class="flex gap-4" x-data="{ enabled: @entangle('enabledOrigins') }">
            @foreach ($origins as $origin)
                <x-origins.item
                    x-on:click="enabled[{{ $origin->id }}] = ! enabled[{{ $origin->id }}]"
                    :origin="$origin"
                    :class="in_array($origin->id, $enabled->toArray()) ? 'bg-white text-black' : ''"
                />
            @endforeach
        </ul> --}}
    </x-header>

    @if ($pulls->isNotEmpty())
        <x-container class="pt-0 p-4">
            <x-card>
                <x-grids.pulls :$pulls />
            </x-card>
        </x-container>
    @endif
</div>
