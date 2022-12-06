<div
    class="flex p-2 hover:p-1 transition-all"
    style="
        grid-column: span {{ $pull->columns }};
        grid-row: span {{ $pull->rows }};
        aspect-ratio: {{ $pull->columns }}/{{ $pull->rows }};
    "
>
    <div
        style="background-image: url('{{ $pull->image->format('resized') }}')"
        {{ $attributes->only('class')->merge(['class' => '
            flex align-stretch w-full rounded-xl
            bg-surface bg-cover bg-center
        ']) }}
    ></div>
</div>
