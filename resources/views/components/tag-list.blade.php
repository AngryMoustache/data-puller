<ul {{ $attributes->only('class')->merge([
    'class' => 'flex gap-1'
]) }}>
    @foreach ($tags as $tag)
        <li>
            <x-tag :$tag />
        </li>
    @endforeach
</ul>
