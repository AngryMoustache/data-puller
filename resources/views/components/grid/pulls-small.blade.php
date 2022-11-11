<div class="grid grid-cols-4 gap-4">
    @foreach ($pulls as $pull)
        <x-card.pull :$pull />
    @endforeach
</div>
