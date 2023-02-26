@props([
    'pull' => null,
    'nameLength' => 25,
])

<div class="flex justify-between items-center p-4 px-6 gap-4">
    <div class="flex flex-col w-full">
        <div class="flex gap-2">
            <p>{{ Str::limit($pull->name, $nameLength) }}</p>
            <span class="text-dark">#{{ $pull->id }}</span>
        </div>

        <div class="opacity-50">
            {{ $pull->formattedViews }} views
        </div>
    </div>

    <div>
        @isset ($pull->origin)
            <x-origin-icon
                :origin="$pull->origin"
                :href="$pull->source_url"
            />
        @endisset
    </div>
</div>
