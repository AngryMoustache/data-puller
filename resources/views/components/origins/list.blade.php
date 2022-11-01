<ul {{ $attributes->except('origins')->merge([
    'class' => 'flex gap-4'
]) }}>
    @foreach ($origins as $origin)
        <x-origins.item :origin="$origin" />
    @endforeach
</ul>
