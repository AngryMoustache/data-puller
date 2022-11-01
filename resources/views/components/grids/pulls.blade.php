@props([
    'highlight' => true,
    'pulls' => [],
])

<x-grids.grid>
    @foreach ($pulls as $pull)
        <x-cards.pull
            :pull="$pull"
            :class="($highlight && $loop->index % 19 === 0) ?
                'col-span-2 row-span-2'
            : ''"
        />
    @endforeach
</x-grids.grid>
