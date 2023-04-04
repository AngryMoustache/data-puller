<x-container class="w-full flex flex-col relative md:flex-row gap-8">
    <div class="
        w-full flex flex-col gap-4
        md:sticky md:top-2 md:w-1/3 md:min-h-feed
    ">
        @foreach ($pull->videos as $video)
            <x-video :src="$video->path()" class="w-full rounded" />
        @endforeach

        @foreach ($attachments as $image)
            <x-img
                wire:key="image-list-{{ $image->id }}"
                :src="$image->path()"
                class="w-full rounded"
            />
        @endforeach
    </div>

    <div class="w-full md:w-2/3 flex flex-col gap-8 py-4">
        <div class="w-full flex flex-col gap-4">
            <x-headers.h1 :text="$pull->name" />

            <p>
                <span class="opacity-50">Pulled</span>
                <span class="mx-1">{{ $pull->created_at->diffForHumans() }}</span>
                <span class="opacity-50">by</span>
                <x-origin class="mx-2" :origin="$pull->origin" href="{{ $pull->source_url }}" />
            </p>
        </div>

        <x-alpine.collapsible :open="true" title="General information">
            <div class="flex flex-col gap-4">
                <x-form.input
                    label="Name"
                    placeholder="Name of the pull"
                    wire:model.defer="fields.name"
                />

                <x-form.input
                    label="Artist name"
                    placeholder="Name of the artist"
                    :value="$pull->name"
                    wire:model.defer="fields.artist"
                />
            </div>
        </x-alpine.collapsible>

        <x-alpine.collapsible title="Media" :open="true">
            <div class="flex flex-col gap-4">
                <div
                    class="flex flex-col gap-4"
                    wire:sortable="updateMediaOrder"
                    wire:loading.class="opacity-50"
                    wire:key="attachments-{{ $attachments->pluck('id')->join('-') }}"
                >
                    @foreach ($attachments as $key =>  $image)
                        <div
                            wire:sortable.item="{{ $image->id }}"
                            wire:key="media-{{ $key }}-{{ $image->id }}"
                            class="
                                flex items-center gap-4
                                p-2 border border-border rounded-lg bg-surface
                            "
                        >
                            <x-heroicon-o-bars-3
                                wire:sortable.handle
                                class="w-12 h-6 cursor-move"
                            />

                            <div class="w-16 h-16">
                                <x-img
                                    :src="$image->format('thumb')"
                                    class="w-full rounded-lg"
                                />
                            </div>

                            <div class="grow">
                                <p>{{ $image->original_name }}</p>
                                <p class="opacity-50 text-sm">
                                    {{ $image->width }}px x {{ $image->height }}px
                                </p>
                            </div>

                            <x-heroicon-o-trash
                                class="w-12 h-6 cursor-pointer hover:text-primary"
                                wire:click="removeAttachment({{ $image->id }})"
                            />
                        </div>
                    @endforeach
                </div>

                <div class="flex">
                    <x-form.button-secondary
                        text="Add attachment"
                        x-on:click="window.openModal('add-attachment', {
                            selected: {{ json_encode($pull->attachments->pluck('id')->toArray()) }},
                        })"
                    />
                </div>
            </div>
        </x-alpine.collapsible>

        <x-alpine.collapsible title="Tags">
            <div class="flex flex-col gap-4">
                @foreach ($tags as $group)
                    <x-alpine.collapsible :open="$loop->first" :title="$group->name">
                        <div x-show="open" class="pt-2">
                            <x-form.tag-tree :tag="$group" />
                        </div>
                    </x-alpine.collapsible>
                @endforeach
            </div>
        </x-alpine.collapsible>

        <div class="flex justify-end gap-4">
            <x-form.button-secondary
                wire:click="save('offline')"
                text="Archive"
            />

            <x-form.button
                wire:click="save('online')"
                text="Publish"
            />
        </div>
    </div>
</x-container>
