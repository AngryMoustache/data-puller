<x-container class="grid grid-cols-5 gap-8">
    @foreach ($pulls as $pull)
        <x-cards.pull :$pull />
    @endforeach
</x-container>
