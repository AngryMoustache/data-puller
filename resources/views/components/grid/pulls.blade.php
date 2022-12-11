@props([
    'pulls' => [],
    'display' => \App\Enums\Display::COMPACT,
])

@if($display === \App\Enums\Display::COMPACT)

    <x-grid.pulls-compact class="hidden sm:block" :$pulls size="5" />
    <x-grid.pulls-compact class="block sm:hidden" :$pulls size="2" />

@else

    <div class="grid grid-cols-4 gap-4">
        @foreach($pulls as $pull)
            <x-pull.card :$pull />
        @endforeach
    </div>

@endif
