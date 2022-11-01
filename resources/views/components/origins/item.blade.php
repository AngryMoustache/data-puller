<div {{ $attributes->except('origin')->merge([
    'class' => '
        flex gap-3 border border-white rounded-3xl py-1 px-4 items-center text-white cursor-pointer
        hover:shadow-lg
    '
]) }}>
    <i class="{{ $origin->type->icon() }}"></i>
    {{ $origin->name }}
</div>
