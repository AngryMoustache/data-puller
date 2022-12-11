<a href="{{ $folder->route() }}" {{ $attributes->merge([
    'class' => 'inline-block rounded-lg px-4 py-1 border'
]) }}>
    {{ $folder->name }}
</a>
