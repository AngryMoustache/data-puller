<div {{ $attributes->except('origin')->merge([
    'class' => 'flex border border-white rounded-3xl p-1 items-center text-white'
]) }}>
    <img
        src="{{ $origin->attachment->format('thumb') }}"
        class="rounded-3xl w-6"
    >

    <span class="px-2">
        {{ $origin->name }}
    </span>
</div>
