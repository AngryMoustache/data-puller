<x-modal disable-overlay-click>
    <x-slot:main>
        <div class="w-full flex flex-col gap-8 py-4">
            <x-headers.h2 text="Choose a group to add" class="p-2" />

            <div class="w-full grid grid-cols-2 gap-4">
                @forelse ($groups as $group)
                    <x-lists.tag-group
                        :buttons="false"
                        :$group
                        class="hover:bg-background cursor-pointer"
                        x-on:click="$wire.call('select', {{ $group->id }})"
                    />
                @empty
                    <p class="opacity-50">No saved groups found</p>
                @endforelse
            </div>
        </div>
    </x-slot>

    <x-slot:footer>
        <x-form.button-secondary
            text="Cancel"
            x-on:click="window.closeModal()"
        />
    </x-slot>
</x-modal>
