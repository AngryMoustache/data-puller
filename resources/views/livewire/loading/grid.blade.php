<x-grid wire:init="ready">
    @for ($i = 0; $i < $size; $i++)
        <x-surface class="w-full flex flex-col gap-4 animate-pulse">
            <div
                class="overflow-hidden rounded bg-text opacity-10"
                style="aspect-ratio: 3/2.5"
            ></div>

            <div class="flex flex-col gap-4">
                <span class="h-4 w-2/3 bg-text opacity-10"></span>

                <div class="flex justify-between opacity-50">
                    <span class="h-4 w-1/5 bg-text opacity-10"></span>
                    <span class="h-4 w-1/5 bg-text opacity-10"></span>
                </div>
            </div>
        </x-surface>
    @endfor
</x-grid>
