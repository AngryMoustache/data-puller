<x-container class="p-8 flex gap-12 relative">
    <div class="w-2/3 flex flex-col gap-12">
        <x-alpine.infinite-scroll :enabled="$hasMore" class="flex flex-col gap-8">
            @foreach ($history as $day => $pulls)
                <div>
                    <a name="{{ $day }}" class="h-0"></a>
                    <x-headers.h1 :text="$day" />
                </div>

                <x-list>
                    @foreach ($pulls as $pull)
                        <x-lists.pull :$pull />
                    @endforeach
                </x-list>
            @endforeach

            <div wire:loading wire:target="loadMore">
                @include('livewire.loading.list', [
                    'size' => 2,
                ])
            </div>
        </x-alpine.infinite-scroll>

    </div>

    <div class="w-1/3 h-fit gap-4 sticky top-16">
        <x-headers.h1 text="History" />

        <div class="flex flex-col mt-4">
            @foreach ($history->keys() as $day)
                <a
                    class="p-4 border-b last:border-0 border-border"
                    href="#{{ $day }}"
                >
                    {{ $day }}
                </a>
            @endforeach
        </div>
    </div>
</x-container>
