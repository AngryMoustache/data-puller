<div
    class="flex flex-col gap-4"
    wire:loading.class="opacity-50"
>
    @if ($media->isEmpty())
        <x-no-images />
    @endif

    @foreach ($media as $item)
        <div class="w-full">
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
        </div>
    @endforeach
</div>
