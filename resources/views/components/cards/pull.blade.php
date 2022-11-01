<div
    wire:key="pull_{{ $pull->id }}"
    style="background-image: url('{{ $pull->image->format('thumb') }}')"
    {{ $attributes->except('pull')->merge(['class' => 'w-full aspect-square bg-cover bg-center rounded-lg border']) }}
></div>
