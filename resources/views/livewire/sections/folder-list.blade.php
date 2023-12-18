<div class="flex flex-col gap-4">
    <x-headers.h2 text="Folders" />

    @foreach ($folders as $folder)
        <div
            wire:key="folder-list-{{ $folder->id }}"
            class="flex gap-4 p-2 rounded border border-border"
        >
            <div class="w-14" wire:key="folder-image-{{ $folder->pulls->first() }}">
                @if ($folder->pulls->isNotEmpty())
                    <x-img
                        class="aspect-square rounded"
                        :src="$folder->pulls->first()->attachment?->format('thumb')"
                    />
                @endif
            </div>

            <div class="grow flex flex-col justify-center">
                <p>
                    {{ $folder->name }}
                </p>
                <p class="opacity-50">
                    Contains
                    {{ $folder->pulls->count() }}
                    {{ Str::plural('pull', $folder->pulls->count()) }}
                </p>
            </div>

            <x-form.button-secondary
                href="{{ $folder->route() }}"
                class="m-2 mr-0 flex items-center"
            >
                <x-heroicon-o-eye class="w-5 h-5" />
            </x-form.button-secondary>

            <x-form.button-secondary
                wire:click="toggleFromFolder({{ $folder->id }})"
                class="m-2 flex items-center"
            >
                @if ($folder->pulls->contains($pull))
                    <x-heroicon-o-minus class="w-5 h-5" />
                @else
                    <x-heroicon-o-plus class="w-5 h-5" />
                @endif
            </x-form.button-secondary>
        </div>
    @endforeach

    <x-form.button-secondary
        text="Create new folder"
        class="w-fit"
        x-on:click="window.openModal('new-folder', {
            pullId: {{ $pull->id }},
        })"
    />
</div>
