<div {{ $attributes->only('class') }}>
    <div class="grid grid-cols-5">
        @foreach(GridBuilder::make($pulls, $size) as $pull)
            <x-pull.compact :$pull />
        @endforeach
    </div>
</div>

{{-- <div class="grid-cols-1"></div> --}}
{{-- <div class="grid-cols-2"></div> --}}
{{-- <div class="grid-cols-3"></div> --}}
{{-- <div class="grid-cols-4"></div> --}}
{{-- <div class="grid-cols-5"></div> --}}
{{-- <div class="grid-cols-6"></div> --}}
{{-- <div class="grid-cols-7"></div> --}}
{{-- <div class="grid-cols-8"></div> --}}
