<x-modal>
    <x-surface class="flex flex-col gap-4" x-data="{}">
        <x-headers.h2 text="Folder" class="p-2" />

        <div class="flex flex-col gap-4 px-2">
            <x-form.input
                wire:model.defer="name"
                label="Name"
                class="!bg-background"
                placeholder="Name of the folder"
            />
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
    </x-surface>
</x-modal>
