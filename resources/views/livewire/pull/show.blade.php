<div class="flex gap-8 p-8">
    <div class="w-2/3 flex flex-col gap-4">
        @foreach ($pull->attachments as $image)
            <x-img :src="$image->path()" class="w-full rounded" />
        @endforeach
    </div>

    <div class="w-1/3 flex flex-col gap-8">
        <x-headers.h1 :text="$pull->name" />

        <div>
            <x-origin :origin="$pull->origin" />
        </div>

        <div class="flex flex-col gap-4">
            <x-headers.h2 text="Related pulls" />
            <livewire:sections.related :pull="$pull" />
        </div>
    </div>
</div>
