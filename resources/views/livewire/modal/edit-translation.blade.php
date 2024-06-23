<x-modal disable-overlay-click>
    <x-slot:main>
        <x-headers.h2 text="Edit translation" class="p-2" />

        <div class="flex flex-col gap-4 px-2">
            <div class="flex flex-col gap-2">
                <label for="translation">Original text</label>
                <x-form.textarea
                    id="translation"
                    wire:model="originalText"
                    class="!bg-background"
                    rows="2"
                />
            </div>

            <div class="flex flex-col gap-2">
                <label for="translation">Translated text</label>
                <x-form.textarea
                    id="translation"
                    wire:model="translationText"
                    class="!bg-background"
                    rows="2"
                />
            </div>
        </div>

        <x-headers.h2 text="Used Kanji" class="p-2" />

        <div class="flex flex-col gap-2 px-2">
            @foreach ($kanji as $item)
                <div class="flex gap-2">
                    <div class="flex w-16 items-center justify-center text-2xl">
                        <a href="{{ $item['route'] }}" target="_blank">
                            {{ $item['character'] }}
                        </a>
                    </div>

                    <div style="flex-grow: 1">
                        <x-form.input
                            class="!bg-background"
                            wire:model="kanji.{{ $loop->index }}.meaning"
                        />
                    </div>

                    <x-form.button-secondary
                        text="Remove"
                        wire:click="removeKanji({{ $loop->index }})"
                    />
                </div>
            @endforeach

            <div class="flex gap-2 mt-4 px-2">
                <div class="w-14" x-data="{
                    addKanji (event) {
                        $wire.addKanji(event.target.value)
                        event.target.value = ''
                    }
                }">
                    <x-form.input
                        class="!bg-background"
                        x-on:change="addKanji"
                        maxlength="1"
                    />
                </div>
            </div>
        </div>

        <div class="flex w-full mt-4 gap-4 justify-between">
            <x-form.button-secondary
                text="Delete"
                wire:click="delete"
            />

            <div class="flex gap-4">
                <x-form.button-secondary
                    text="Cancel"
                    x-on:click="window.closeModal()"
                />

                <x-form.button
                    text="Save"
                    wire:click="save"
                />
            </div>
        </div>
    </x-slot>
</x-modal>
