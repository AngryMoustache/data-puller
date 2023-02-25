<x-container class="feed">
    <x-loading-section class="flex gap-4 mt-4 items-start" wire:target="save">
        <div class="w-1/3 flex flex-col gap-4 sticky top-2">
            @foreach ($pull->videos as $video)
                <x-video src="{{ $video->path() }}" />
            @endforeach

            @foreach ($pull->attachments as $image)
                <x-image
                    class="w-full rounded-lg overflow-hidden"
                    src="{{ $image->path() }}"
                />
            @endforeach
        </div>

        <div class="w-2/3 flex flex-col gap-6">
            <x-feed-steps :$maxSteps :$currentStep />

            <x-surface class="flex flex-col">
                @if ($currentStep === 1)
                    <div class="pt-2">
                        <x-form.input
                            :value="$fields['name']"
                            label="Name"
                            wire:model.defer="fields.name"
                        />
                    </div>

                    <div class="pt-6">
                        <x-form.input
                            :value="$fields['artist']"
                            label="Artist name"
                            wire:model.defer="fields.artist"
                        />
                    </div>
                @elseif ($currentStep === 2)
                    @foreach ($mediaForm as $field)
                        <x-rambo::crud.fields.form
                            :resource="$resource"
                            :field="$field"
                            :item="$resource->item"
                        />
                    @endforeach
                @else
                    <x-headers.h4 class="mb-4" :text="$tags[$currentStep - 3]->name" />
                    <div class="feed-tag-list p-2">
                        <x-form.tag-list first :tag="$tags[$currentStep - 3]" />
                    </div>
                @endif
            </x-surface>

            <x-feed-steps :$maxSteps :$currentStep />
        </div>
    </x-loading-section>
</x-container>
