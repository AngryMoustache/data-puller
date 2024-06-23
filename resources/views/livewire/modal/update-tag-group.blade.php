<x-modal disable-overlay-click>
    <x-slot:main>
        <div class="w-full flex flex-col gap-8 py-4">
            <x-headers.h2 text="Group details" class="p-2" />

            <div class="flex flex-col gap-4 px-2 mb-4">
                <x-form.input
                    wire:model="group.name"
                    label="Name"
                    class="!bg-background"
                    placeholder="Name of the tag"
                />
            </div>

            <x-alpine.tabs :tabs="$tags->pluck('name', 'id')">
                @foreach ($tags as $tag)
                    <x-alpine.tab tab-key="{{ $tag->id }}">
                        <x-form.tag-tree :$tag />
                    </x-alpine.tab>
                @endforeach
            </x-alpine.tabs>
        </div>
    </x-slot>

    <x-slot:footer>
        <x-form.button-secondary
            text="Cancel"
            x-on:click="window.closeModal()"
        />

        <x-form.button
            text="Save"
            wire:click="save"
        />
    </x-slot>
</x-modal>
