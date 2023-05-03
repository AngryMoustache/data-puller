@props([
    'folder',
])

<div {{ $attributes->except('folder')->merge([
    'class' => 'flex gap-4 p-2 rounded border border-border'
]) }}>
    <div class="w-14">
        <x-img
            class="aspect-square rounded"
            :src="$folder->pulls->first()?->attachment?->format('thumb')"
        />
    </div>

    <div class="grow flex flex-col justify-center">
        <p>
            {{ $folder->name }}
        </p>
        <p class="opacity-50">
            Contains
            {{ $folder->pulls->count() }}
            {{ Str::plural('pull', $folder->pulls->count()) }}
        </p>
    </div>

    {{ $slot }}
</div>
