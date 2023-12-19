<div class="flex flex-col gap-4">
    <x-form.input
        wire:model.debounce.live="query"
        placeholder="Search on name"
    />

    <div class="flex flex-col gap-4">
        @forelse ($artists as $artist)
            <div class="flex gap-2 items-center">
                <span
                    class="ml-2 p-2 text-xl text-primary cursor-pointer rounded hover:bg-dark-hover hover:text-text"
                    x-on:click="window.openModal('edit-artist', {
                        id: @js($artist->id),
                    })"
                >
                    <x-heroicon-o-pencil class="w-4 h-4" />
                </span>

                <div>
                    {{ $artist->name }}<br>
                    <span class="opacity-50">
                        {{ $artist->pulls_count }} {{ Str::plural('pull', $artist->pulls_count) }}
                    </span>
                </div>
            </div>
        @empty
            <span class="opacity-50">
                No artists found
            </span>
        @endforelse
    </div>
</div>
