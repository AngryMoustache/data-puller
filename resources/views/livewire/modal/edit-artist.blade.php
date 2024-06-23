<x-modal>
    <x-slot:main>
        <x-headers.h2 text="Edit artist" class="p-2" />

        <div class="flex flex-col gap-4 px-2">
            <x-form.input
                wire:model="name"
                label="Name"
                class="!bg-background"
                placeholder="Name of the artist"
            />

            <x-form.autocomplete
                label="Children"
                placeholder="Children of the artist"
                wire-model="childSearch"
                :options="$artistList->toArray()"
                select-event="selectChild"
                class="!bg-background"
            />

            @if (count($children) > 0)
                <div class="flex">
                    <label class="w-32"></label>

                    <div class="flex flex-wrap gap-2">
                        @foreach ($children as $child)
                            <div class="px-4 py-2 border border-border rounded-xl flex gap-4 items-center">
                                {{ $child['value'] }}

                                <span
                                    class="text-xl text-primary cursor-pointer rounded hover:text-text"
                                    wire:click="removeChild({{ $child['key'] }})"
                                >
                                    <x-heroicon-o-trash class="w-4 h-4" />
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="flex w-full mt-4 gap-4 justify-end">
            <x-form.button-secondary
                text="Cancel"
                x-on:click="window.closeModal()"
            />

            <x-form.button
                text="Save"
                wire:click="save"
            />
        </div>
    </x-slot>
</x-modal>
