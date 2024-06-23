<x-modal>
    <x-slot:main class="flex flex-col gap-4" x-data>
        <x-headers.h2 text="Adding an existing tag group folder" class="p-2" />

        <div class="flex gap-2">
            <div class="w-1/3 flex flex-col gap-4">
                <livewire:global-search is-pull-index />
                <x-form.selected-filters :$filters />
            </div>

            <div class="w-2/3 grid grid-cols-6 gap-2">
                @foreach ($pulls as $pull)
                    <x-cards.pull
                        wire:key="{{ $pull->id }}"
                        :$pull
                        x-on:click.prevent="$wire.selectPull({{ $pull->id }})"
                    />
                @endforeach
            </div>
        </div>
    </x-slot>
</x-modal>
