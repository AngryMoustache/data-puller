<div {{ $attributes->merge(['class' => '
    grid gap-4
    grid-cols-1
    md:grid-cols-2
    lg:grid-cols-6
']) }}>
    {{ $slot }}
</div>
