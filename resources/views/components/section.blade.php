@props(['label'])

<div {{ $attributes->only('class')->merge([
    'class' => 'w-full flex flex-col gap-8',
]) }}>
    <x-headers.h2 :text="$label" />

    {{ $slot }}
</div>
