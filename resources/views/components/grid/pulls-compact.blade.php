<div {{ $attributes->merge() }}>
    <div class="grid grid-cols-{{ $size }}">
        @foreach(GridBuilder::make($pulls, $size) as $pull)
            <x-pull.compact :$pull />
        @endforeach
    </div>
</div>
