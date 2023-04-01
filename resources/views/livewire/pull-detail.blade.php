<div class="flex gap-8 my-4">
    <div class="w-2/3 flex flex-col gap-4">
        @foreach ($pull->attachments as $image)
            <x-img :src="$image->path()" class="w-full rounded" />
        @endforeach
    </div>

    <div class="w-1/3">
        <h1>{{ $pull->name }}</h1>
    </div>
</div>
