<x-container class="flex flex-col gap-12 py-12">
    <x-headers.h2>My folders</x-headers.h2>

    <x-grid class="gap-4">
        @foreach ($folders as $folder)
            <x-cards.folder :$folder />
        @endforeach
    </x-grid>

    <x-headers.h2>Dynamic folders</x-headers.h2>

    <x-grid class="gap-4">
        @foreach ($dynamicFolders as $folder)
            <x-cards.folder :$folder />
        @endforeach
    </x-grid>
</x-container>
