@props(['size' => 5])

<x-list wire:init="ready">
    @for ($i = 0; $i < $size; $i++)
        <div class="w-full flex gap-4 animate-pulse">
            <div
                class="w-64 overflow-hidden rounded bg-text opacity-10"
                style="aspect-ratio: 3/2.5"
            ></div>

            <div class="w-full flex flex-col gap-4 p-3">
                <span class="h-5 w-1/3 bg-text opacity-10"></span>
                <span class="h-5 w-2/3 bg-text opacity-10"></span>

                <div class="w-full flex flex-col gap-1">
                    <span class="h-5 w-4/5 bg-text opacity-10"></span>
                    <span class="h-5 w-3/5 bg-text opacity-10"></span>
                </div>
            </div>
        </div>
    @endfor
</x-list>
