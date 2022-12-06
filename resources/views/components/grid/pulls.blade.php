@props([
    'pulls' => [],
    'display' => null,
])

@if($display === \App\Enums\Display::COMPACT)

    <x-grid.pulls-compact class="hidden lg:block" :$pulls size="5" />
    <x-grid.pulls-compact class="hidden md:block lg:hidden" :$pulls size="4" />
    <x-grid.pulls-compact class="block md:hidden" :$pulls size="2" />

@else

    <div class="grid grid-cols-4 gap-4">
        @foreach($pulls as $pull)
            <x-pull.card :$pull />
        @endforeach
    </div>

@endif
