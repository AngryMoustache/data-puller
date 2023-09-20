<x-modal
    disable-overlay-click
    full-screen
>
    <x-slot:main>
        <div class="w-full flex gap-4">
            <div
                style="height: 80vh"
                class="w-3/5"
                x-data="{
                    init () {
                        if (window.cropper !== undefined) {
                            window.cropper.destroy()
                        }

                        window.cropper = new Cropper(
                            document.getElementById('cropper-frame'),
                            {
                                ...@js($options),
                                viewMode: 1,
                                dragMode: 'move',
                                ready() {
                                    this.cropper.setData(@js($initial))
                                },
                            }
                        )
                    },
                }"
            >
                <div class="w-full flex gap-4">
                    <div class="w-full aspect-square block overflow-hidden h-[80vh]">
                        <img
                            id="cropper-frame"
                            src="{{ $attachment->path() }}"
                            wire:key="cropper-{{ $attachment->id }}"
                            class="max-w-full h-[80vh]"
                        />
                    </div>
                </div>
            </div>

            <div
                class="flex flex-col gap-2 w-2/5 overflow-y-auto pb-3"
                style="height: 80vh"
                x-data="{
                    thumbnail: @entangle('thumbnail'),
                }"
            >
                <x-headers.h2 text="Link thumbnail to tag(s)" />

                <x-form.checkbox
                    label="This is the main thumbnail image"
                    x-model="thumbnail.is_main"
                />

                <div
                    class="flex flex-col gap-4 mt-4"
                    x-show="! thumbnail.is_main"
                    x-transition
                >
                    @foreach ($tagGroups as $tagGroup)
                        <x-alpine.collapsible :open="$loop->first">
                            <x-slot:title>
                                @if ($tagGroup['is_main'])
                                    <x-heroicon-s-bookmark class="w-4 h-4 text-primary mr-2" />
                                @endif

                                {{ $tagGroup['name'] }}
                            </x-slot:title>

                            @foreach ($tagGroup['tags'] as $tag)
                                <x-form.checkbox
                                    :label="$tag['name']"
                                    :value="$tag['id']"
                                    x-model="thumbnail.tags[{{ $tagGroup['id'] }}]"
                                />
                            @endforeach
                        </x-alpine.collapsible>
                    @endforeach
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot:footer>
        <x-form.button-secondary
            text="Cancel"
            x-on:click="window.closeModal()"
        />

        <x-form.button
            text="Save format"
            x-on:click="$wire.dispatch('save-crop', [{
                data: window.cropper.getData(),
                crop: window.cropper
                    .getCroppedCanvas({ width: 500, height: 500})
                    .toDataURL('{{ $attachment->mime_type }}'),
            }])"
        />
    </x-slot>
</x-modal>
