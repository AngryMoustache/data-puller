<x-modal
    width="w-1/2"
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
        <div class="flex flex-col gap-8">
            <div class="w-full">
                <x-form.input
                    class="!bg-background"
                    wire:model.debounce.live="query"
                    placeholder="Search for a pull of attachment name"
                />
            </div>

            @if (! $forceLoading)
                <div class="w-full flex align-start items-start h-[60vh] p-1">
                    <div class="flex items-center justify-center overflow-y-scroll w-full">
                        <div wire:loading class="w-full items-center py-8 justify-center">
                            <x-loading />
                        </div>

                        <div
                            wire:loading.remove
                            class="
                                grid grid-cols-3 md:grid-cols-5 gap-4
                                w-full
                            "
                        >
                            @foreach ($attachments as $attachment)
                                <div
                                    wire:key="attachment-{{ $attachment->id }}"
                                    class="relative rounded cursor-pointer aspect-square"
                                    x-bind:class="{
                                        'p-2 border border-primary': selected.includes({{ $attachment->jsonId() }}),
                                    }"
                                >
                                    @if ($attachment->isVideo)
                                        <span
                                            class="absolute top-1 right-1 bg-black bg-opacity-75 rounded px-2 py-1 text-xs"
                                            x-bind:class="{
                                                'top-3 right-3': selected.includes({{ $attachment->jsonId() }})
                                            }"
                                        >
                                            <x-heroicon-s-play class="w-4 h-4 text-text" />
                                        </span>
                                    @endif

                                    <x-img
                                        class="rounded"
                                        :src="$attachment->format('thumb')"
                                        x-on:click="selectItem({{ $attachment->jsonId() }})"
                                    />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex justify-center gap-4 w-full">
                    <x-form.button-secondary text="<<" wire:click="previousPage" />

                    <div class="w-1/3">
                        <x-form.input
                            type="number"
                            class="text-center"
                            x-on:change.live="$wire.setPage($event.target.value)"
                            value="{{ $paginators['page'] }}"
                        />
                    </div>

                    <x-form.button-secondary text=">>" wire:click="nextPage" />
                </div>
            @else
                <div class="w-full flex items-center py-8 justify-center">
                    <x-loading />
                </div>
            @endif
        </div>
    </x-slot>

    <x-slot:footer>
        <x-form.button
            text="Upload new"
            x-on:click.prevent="window.openModal('upload-pull')"
        />

        <div class="grow"></div>

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
