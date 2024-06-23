<div x-data="{}">
    <x-alpine.collapsible title="Folders">
        <div class="flex flex-col gap-4">
            <div class="rounded border border-border">
                @foreach ($folders as $folder)
                    <div
                        wire:key="folder-list-{{ $folder->id }}"
                        class="flex gap-4 p-2 border-b border-border last:border-b-0"
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
                            <a href="{{ $folder->route() }}">
                                {{ $folder->name }}
                            </a>
                            <p class="opacity-50">
                                Contains
                                {{ $folder->pulls->count() }}
                                {{ Str::plural('pull', $folder->pulls->count()) }}
                            </p>
                        </div>

                        <x-form.checkbox
                            wire:change="toggleFromFolder({{ $folder->id }})"
                            class="m-2 flex items-center"
                            :checked="$folder->pulls->contains($pull) ? 'checked' : null"
                        />
                    </div>
                @endforeach
            </div>

            <x-form.button-secondary
                text="Create new folder"
                class="w-fit"
                x-on:click="window.openModal('new-folder', {
                    pullId: {{ $pull->id }},
                })"
            />
        </div>
    </x-alpine.collapsible>
</div>
