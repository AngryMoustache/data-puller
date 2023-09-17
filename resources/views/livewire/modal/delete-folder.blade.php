<x-modal>
    <x-slot:main class="flex flex-col gap-4" x-data="{}">
        <x-headers.h2 text="Deleting folder" class="p-2" />

        <div class="flex flex-col gap-4 px-2">
            <p>You are about to delete '{{ $name }},' are you sure?</p>
        </div>

        <div class="flex w-full mt-4 gap-4 justify-end">
            <x-form.button-secondary
                text="Cancel"
                x-on:click="window.closeModal()"
            />

            <x-form.button
                text="Yes, delete it"
                wire:click="delete"
            />
        </div>
    </x-slot>
</x-modal>
