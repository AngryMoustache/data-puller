<x-container class="p-8 flex gap-12 relative">
    <div wire:key="loading" class="w-full" wire:loading>
        <x-loading />
    </div>

    <div class="w-full flex flex-col gap-8" wire:key="loaded" wire:loading.remove>
        <div class="grid grid-cols-6 gap-2">
            @foreach ($items as $item)
                <div class="flex flex-col gap-4 p-4 w-full bg-surface rounded-xl">
                    <div
                        class="w-full aspect-square bg-cover bg-center rounded"
                        style="background-image: url('{{ $item->url }}'), url('{{ asset('images/pixel.png') }}')"
                    ></div>

                    <div class="flex gap-2 justify-between">
                        <x-form.button-secondary target="_blank" href="{{ $item->url }}">
                            <x-heroicon-o-eye class="w-5 h-5" />
                        </x-form.button-secondary>

                        <x-form.button-secondary wire:click="delete('{{ $item->url }}')">
                            <x-heroicon-o-trash class="w-5 h-5" />
                        </x-form.button-secondary>

                        <x-form.button wire:click="pull('{{ $item->url }}')">
                            <x-heroicon-o-inbox-arrow-down class="w-5 h-5" />
                        </x-form.button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-container>
