<x-modal>
    <x-slot:main class="flex flex-col gap-4" x-data>
        <x-headers.h2 text="Adding an existing tag group folder" class="p-2" />

        <div class="grid grid-cols-2 gap-4">
            @foreach ($groups as $group)
                <div
                    x-on:click.prevent="$wire.selectGroup({{ $group->id }})"
                    class="
                        flex items-center gap-4
                        px-4 py-3 border border-border rounded-lg bg-surface
                        cursor-pointer hover:bg-background transition-all
                    "
                >
                    <div class="flex-grow flex flex-col">
                        <x-headers.h3 class="gap-2">
                            @if ($group->is_main)
                                <x-heroicon-s-bookmark class="w-4 h-4 text-primary" />
                            @endif

                            {{ $group->name }}
                        </x-headers.h3>

                        <p class="opacity-50">
                            Contains {{ $group->tags()->count() }} tags
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex">
            <x-form.button-secondary
                text="Back to overview"
                x-on:click="$wire.set('step', 1)"
            />
        </div>
    </x-slot>
</x-modal>
