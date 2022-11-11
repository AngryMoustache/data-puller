@props([
    'pulls' => [],
    'display' => null,
])

@if ($display === \App\Enums\Display::COMPACT)

    <div class="grid grid-cols-6 gap-4">
        @foreach ($pulls as $pull)
            <x-compact.pull :$pull />
        @endforeach
    </div>

@elseif ($display === \App\Enums\Display::LIST)

    <div class="grid grid-cols-4 gap-4">
        @foreach ($pulls as $pull)
            <x-card.pull :$pull />
        @endforeach
    </div>

@else

    <div class="grid grid-cols-4 gap-4">
        @foreach ($pulls as $pull)
            <x-card.pull :$pull />
        @endforeach
    </div>

@endif
