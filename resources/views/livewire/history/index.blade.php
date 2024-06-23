<x-container class="p-8 flex gap-12 relative">
    <div wire:key="loading" class="w-full md:w-2/3" wire:loading>
        <x-loading />
    </div>

    <div class="w-full md:w-2/3 flex flex-col gap-8" wire:key="loaded" wire:loading.remove>
        <x-headers.h1 :text="$current" />

        <x-list wire:key="history-{{ $current }}">
            @foreach ($history as $pull)
                <x-lists.pull :$pull />
            @endforeach
        </x-list>
    </div>

    <div class="w-1/3 h-fit gap-4 hidden md:block sticky top-16">
        <x-headers.h1 text="History" />

        <div class="flex flex-col mt-4">
            @foreach ($days->take(14) as $day)
                <span class="w-full px-2 py-3 border-b last:border-0 border-border">
                    <a
                        wire:click="changeDay('{{ $day }}')"
                        @class([
                            'p-3 w-full block hover:bg-surface rounded-lg cursor-pointer',
                            'bg-surface' => $day === $current,
                        ])
                    >
                        {{ $day }}
                    </a>
                </span>
            @endforeach
        </div>
    </div>
</x-container>
