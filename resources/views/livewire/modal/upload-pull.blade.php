<x-modal disable-overlay-click>
    <x-slot:main>
        <x-headers.h2 text="Upload media" />

        <div wire:loading>
            <x-loading />
        </div>

        <div
            class="flex flex-col gap-4 p-4"
            wire:loading.remove
        >
            <x-form.input
                label="Files"
                type="file"
                multiple
                wire:model="uploadField"
                class="!bg-background"
            />

            @if (count($media) > 0)
                <div class="flex flex-col gap-4">
                    @foreach ($media as $key => $file)
                        <div class="
                            flex items-center gap-4
                            p-2 border border-border rounded-lg bg-background
                        ">
                            <div
                                class="w-16 h-16 bg-border rounded-lg bg-cover bg-center"
                                style="background-image: url('{{ $file->temporaryUrl() }}');"
                            ></div>

                            <div class="grow">
                                <p class="line-clamp-1">
                                    {{ $file->getClientOriginalName() }}
                                </p>

                                <p class="opacity-50 text-sm">
                                    {{ app('site')->bytesToHuman($file->getSize()) }}
                                </p>
                            </div>

                            <x-heroicon-o-trash
                                class="w-12 h-6 cursor-pointer hover:text-primary"
                                x-on:click="$wire.removeFile({{ $key }})"
                            />
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </x-slot>

    <x-slot:footer>
        <x-form.button-secondary
            text="Cancel"
            x-on:click="window.closeModal()"
        />

        <x-form.button
            text="Upload"
            wire:click="save"
        />
    </x-slot>
</x-modal>
