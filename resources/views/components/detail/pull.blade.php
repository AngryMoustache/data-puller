<div class="flex flex-col md:flex-row gap-8">
    <div class="w-full md:w-1/2">
        <img class="border w-full rounded-lg" src="{{ $pull->image->path() }}">
    </div>

    <div class="w-full md:w-1/2 py-2">
        <x-headers.h2 class="pb-0 text-black">
            {{ $pull->name }}
        </x-headers.h2>

        <x-headers.h3 class="pb-0 text-black">
            Pulled: {{ $pull->pulledWhen }}
        </x-headers.h3>

        <x-headers.h3 class="text-black">
            Origin:
            <a href="{{ $pull->source_url }}" target="_blank">
                {{ $pull->origin->label() }}
                <i class="ml-1 {{ $pull->origin->icon() }}"></i>
            </a>
        </x-headers.h3>

        <x-headers.h2 class="text-black">Tags</x-headers.h2>
        <x-tag-list class="pb-4" :tags="$pull->tags" />

        <x-headers.h2 class="text-black">More like this</x-headers.h2>
        <x-grids.pulls :highlight="false" :pulls="$pull->related->shuffle()->take(6)" />
    </div>
</div>
