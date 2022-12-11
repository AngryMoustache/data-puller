<x-container>
    <div class="grid grid-cols-3 gap-4">
        @foreach ($folders as $folder)
            <a href="{{ $folder->route() }}">
                <x-surface class="flex gap-4 items-center">
                    <div class="w-1/4">
                        <x-image class="rounded-lg" :src="$folder->image->format('thumb')" />
                    </div>

                    <div class="flex flex-col">
                        <x-headers.h4 :text="$folder->name" />
                        <p class="opacity-50">{{ $folder->description }}</p>
                        <p class="mt-4">
                            Contains <x-highlight :text="$folder->pulls->count()" /> pulls
                        </p>
                    </div>
                </x-surface>
            </a>
        @endforeach
    </div>
</x-container>
