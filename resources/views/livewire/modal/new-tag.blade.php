<x-modal>
    <x-surface class="flex flex-col gap-4">
        <x-headers.h3 text="New tag" class="p-2" />

        <x-form.select
            wire:model.defer="group"
            label="Name"
            :options="$groups"
        />

        <x-form.input
            wire:model.defer="name"
            label="Name"
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
