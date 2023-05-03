@props([
    'id' => Str::random(16),
    'label' => null,
])

<div class="flex w-full items-center gap-4">
    @if ($label)
        <label class="w-32" for="{{ $id }}">
            {{ $label }}
        </label>
    @endif

    <input id="{{ $id }}" {{ $attributes->except('label')->merge([
        'class' => 'bg-surface px-4 py-2 text-lg rounded-xl w-full outline-none',
    ]) }} />
</div>
