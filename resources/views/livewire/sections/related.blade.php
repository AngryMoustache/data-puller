<div class="flex flex-col gap-8">
    @foreach ($pulls as $pull)
        <x-lists.pull :$pull />
    @endforeach
</div>
