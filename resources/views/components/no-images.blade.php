<div class="bg-black relative w-full aspect-square overflow-hidden">
    <div class="absolute inset-0 flex justify-center items-center">
        <div class="flex flex-col gap-4 items-center z-10">
            <x-heroicon-o-code-bracket class="w-16 h-16" />
            <x-headers.h3 class="text-3xl" text="NO IMAGES" />
        </div>
    </div>

    <div class="opacity-10 z-0">
        <x-img
            class="rounded"
            wire:key="no-images"
            :src="app('site')->randomImage()"
        />
    </div>
</div>
