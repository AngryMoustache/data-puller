<div x-data="{
    init () {
        this.initCropper()

        window.addEventListener('init-cropper', ({ detail }) => {
            this.initCropper()
        })
    },
    initCropper () {
        const image = document.getElementById('cropper-frame')

        image.style.maxWidth = image.parentElement.clientWidth + 'px'
        image.style.minHeight = image.parentElement.clientHeight + 'px'

        if (window.cropper !== undefined) {
            window.cropper.destroy()
        }

        window.cropper = new Cropper(image, {
            ...@js($options),
            viewMode: 1,
            dragMode: 'move',
            ready() {
                this.cropper.setData(@js($initial))
            },
        })
    },
}">
    <div wire:loading.flex>
        <x-loading />
    </div>

    <div wire:loading.remove>
        <div class="w-full flex gap-4">
            <div class="w-full aspect-square block overflow-hidden">
                <img
                    id="cropper-frame"
                    src="{{ $attachment->path() }}"
                    wire:key="cropper-{{ $attachment->id }}"
                />
            </div>

            <div class="flex flex-col gap-4 w-64">
                <x-form.button
                    text="Save format"
                    class="text-center"
                    x-on:click="$wire.dispatch('cropped', [{
                        data: window.cropper.getData(),
                        crop: window.cropper
                            .getCroppedCanvas({ width: 500, height: 500})
                            .toDataURL('{{ $attachment->mime_type }}'),
                    }])"
                />

                <x-form.button-secondary
                    text="Reset"
                    x-on:click="window.cropper.reset()"
                    class="text-center"
                />
            </div>
        </div>
    </div>
</div>
