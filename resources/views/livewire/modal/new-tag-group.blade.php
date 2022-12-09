<x-modal>
    <x-surface class="flex flex-col gap-4">
        <x-headers.h3 text="New tag" class="p-2" />

        <x-form.input
            wire:model.defer="name"
            label="Name"
        />

        <x-form.textarea
            wire:model.defer="description"
            label="Description (optional)"
            class="h-64"
        />

        <div class="flex w-full mt-4 justify-end">
            <x-form.button-secondary
                label="Cancel"
                x-data="{}"
                x-on:click="$wire.emit('closeModal')"
            />

            <x-form.button
                label="Create"
                wire:click="save()"
            />
        </div>
    </x-surface>
</x-modal>
