<x-modal class="w-screen">
    <x-surface class="flex flex-col gap-4" x-data="{
        selected: {{ json_encode($selected) }},
    }">
        <x-headers.h2 text="Select an attachment" class="p-2" />

        <div class="flex gap-8">
            <div class="w-1/4 flex flex-col gap-2">
                @foreach ($pulls as $pull)
                    <x-form.button-secondary
                        :text="$pull->name"
                        wire:click="selectPull({{ $pull->id }})"
                        @class(['opacity-50' => $pull->id === $pullId])
                    />
                @endforeach

                <div class="flex justify-between gap-4 w-full pt-4 mt-2 border-t border-border">
                    <x-form.button-secondary text="<<" wire:click="previousPage" />
                    <x-form.button-secondary text=">>" wire:click="nextPage" />
                </div>
            </div>

            @if (! $forceLoading)
                <div class="w-3/4" wire:key="{{ $pullId }}">
                    <div wire:loading.flex class="w-full items-center py-8 justify-center">
                        <x-loading />
                    </div>

                    <div class="grid grid-cols-3 md:grid-cols-6 gap-4" wire:loading.remove>
                        @foreach ($attachments as $attachment)
                            <div
                                wire:key="attachment-{{ $attachment->id }}"
                                class="rounded cursor-pointer"
                                x-bind:class="{
                                    'p-2 border border-primary': selected.includes({{ $attachment->id }}),
                                }"
                            >
                                <x-img
                                    :src="$attachment->format('thumb')"
                                    class="rounded"
                                    x-on:click="selected.includes({{ $attachment->id }})
                                        ? selected = selected.filter(id => id !== {{ $attachment->id }})
                                        : selected.push({{ $attachment->id }})"
                                />
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="w-3/4 flex items-center py-8 justify-center">
                    <x-loading />
                </div>
            @endif
        </div>

        <div class="flex w-full mt-4 gap-4 justify-end">
            <x-form.button-secondary
                text="Cancel"
                x-on:click="window.closeModal()"
            />

            <x-form.button
                text="Add selected attachments"
                x-on:click="$wire.call('addSelected', selected)"
            />
        </div>
    </x-surface>
</x-modal>
