<div class="grid grid-cols-4 gap-4">
    @foreach ($pulls as $pull)
        <x-pull.card :$pull />
    @endforeach
</div>
