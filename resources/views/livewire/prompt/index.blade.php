<x-container>
    <x-alpine.infinite-scroll class="flex flex-col gap-8 md:w-2/3" :enabled="$hasMore">
        <x-headers.h1 text="Prompt history" />

        <x-list>
            @foreach ($prompts as $prompt)
                <x-lists.prompt :$prompt />
            @endforeach
        </x-list>

        @if ($hasMore)
            <div wire:loading wire:target="loadMore">
                @include('livewire.loading.list', ['size' => $perPage])
            </div>
        @endif
    </x-alpine.infinite-scroll>
</x-container>
