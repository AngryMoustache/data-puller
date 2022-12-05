@props([
    'pulls' => [],
    'display' => null,
])

@if ($display === \App\Enums\Display::COMPACT)

    <div class="grid grid-cols-6 gap-4">
        @foreach ($pulls as $pull)
            <x-pull.compact :$pull />
        @endforeach
    </div>

@else

    <div class="grid grid-cols-4 gap-4">
        @foreach ($pulls as $pull)
            <x-pull.card :$pull />
        @endforeach
    </div>

@endif
