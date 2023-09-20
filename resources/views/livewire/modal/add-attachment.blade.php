<x-modal
    full-screen
    x-data="{
        selected: {{ json_encode($selected) }},
        multiple: {{ json_encode($multiple) }},
        selectItem (attachmentId) {
            if (this.multiple) {
                this.selected.includes(attachmentId)
                    ? this.selected = this.selected.filter(id => id !== attachmentId)
                    : this.selected.push(attachmentId)
            } else {
                this.selected = [attachmentId]
                this.submit()
            }
        },
        submit () {
            $wire.call('addSelected', this.selected)
        }
    }"
>
    <x-slot:main>
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
                                    'p-2 border border-primary': selected.includes({{ $attachment->jsonId() }}),
                                }"
                            >
                                <x-img
                                    :src="$attachment->format('thumb')"
                                    class="rounded"
                                    x-on:click="selectItem({{ $attachment->jsonId() }})"
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
    </x-slot>

    <x-slot:footer>
        <x-form.button-secondary
            text="Cancel"
            x-on:click="window.closeModal()"
        />

        @if ($multiple)
            <x-form.button
                text="Add selected attachments"
                x-on:click="submit()"
            />
        @endif
    </x-slot>
</x-modal>
