@props([
    'rating',
    'percentage' => $rating->pivot->rating * 10,
])

<div class="relative aspect-square">

    {{-- RING WITH PARTIAL FILLING --}}
    <div
        class="w-full aspect-square"
        style="clip-path: polygon(0 0, 100% 0, 100% {{ $percentage }}%, 0 {{ $percentage }}%);"
    >
        <div class="absolute inset-0 opacity-25">
            <x-dynamic-component
                :component="$rating->icon"
                class="w-full aspect-square"
            />
        </div>
    </div>
</div>
