<div
    class="flex flex-col gap-4"
    wire:loading.class="opacity-50"
>
    @foreach ($media as $item)
        @if ($item::class === \App\Models\Video::class)
            <x-video :src="$item->path()" class="w-full rounded" />
        @else
            <x-img
                wire:key="image-list-{{ $item->id }}"
                class="rounded"
                :src="$item->path()"
                :width="$item->width"
                :height="$item->height"
            />
        @endif
    @endforeach
</div>
