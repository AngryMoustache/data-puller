<x-grid>
    @foreach ($pulls as $pull)
        <x-cards.pull :$pull />
    @endforeach
</x-grid>
