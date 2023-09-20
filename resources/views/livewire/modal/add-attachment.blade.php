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
                                        <div
                                            class="rounded absolute top-0 left-0 p-1 bg-black bg-opacity-50"
                                            x-bind:class="{
                                                'top-2 left-2': selected.includes({{ $attachment->jsonId() }})
                                            }"
                                        >
                                            <x-heroicon-s-play class="w-8 h-8 text-text" />
                                        </div>
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
