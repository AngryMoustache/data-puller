<x-modal class="flex flex-col gap-8">
    <div class="w-full flex flex-col gap-4">
        @foreach ($pull->attachments as $image)
            <x-image class="w-full rounded-lg overflow-hidden" src="{{ $image->path() }}" />
        @endforeach
    </div>

    <div class="bg-surface rounded-lg">
        <x-pull.info :$pull />
    </div>

    <div>
        <x-grid.pulls-compact :pulls="$pull->related" size="5" />
    </div>
</x-modal>
