<div class="grid grid-cols-4 gap-4">
    @foreach ($pulls as $pull)
        <x-cards.pull :$pull />
    @endforeach
</div>
