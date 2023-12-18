<div
    class="flex flex-col gap-4"
    wire:loading.class="opacity-50"
>
    @if ($media->isEmpty())
        <x-no-images />
    @endif

    @foreach ($media as $item)
        <div class="w-full">
            @if ($item::class === \App\Models\Video::class)
                <div class="w-full flex flex-col bg-surface rounded">
                    <x-video
                        :src="$item->path()"
                        class="w-full"
                        id="video--{{ $item->id }}"
                    />

                    @if ($item->preview_id === null || $showPreviewGenerator)
                        <canvas
                            id="canvas--{{ $item->id }}"
                            style="display: none; overflow:auto"
                        ></canvas>

                        <div
                            class="w-full flex p-2 gap-2"
                            x-data="{
                                capture () {
                                    var video = document.querySelector('#video--{{ $item->id }}')
                                    var canvas = document.querySelector('#canvas--{{ $item->id }}')

                                    canvas.width = video.videoWidth
                                    canvas.height = video.videoHeight

                                    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height)
                                },
                                capturePreview() {
                                    this.capture()

                                    var canvas = document.querySelector('#canvas--{{ $item->id }}')

                                    this.$wire.call(
                                        'capturePreview',
                                        canvas.toDataURL().split(';base64,')[1],
                                        {{ $item->id }},
                                    )
                                },
                                captureImage() {
                                    this.capture()

                                    var canvas = document.querySelector('#canvas--{{ $item->id }}')

                                    this.$wire.call(
                                        'captureImage',
                                        canvas.toDataURL().split(';base64,')[1],
                                        {{ $item->id }},
                                    )
                                },
                            }"
                        >
                            <x-form.button-secondary
                                text="Capture preview"
                                x-on:click="capturePreview()"
                            />

                            <x-form.button-secondary
                                text="Capture image"
                                x-on:click="captureImage()"
                            />
                        </div>
                    @endif
                </div>
            @else
                <x-img
                    wire:key="image-list-{{ $item->id }}"
                    class="rounded"
                    :src="$item->path()"
                    :width="$item->width"
                    :height="$item->height"
                />
            @endif
        </div>
    @endforeach
</div>
