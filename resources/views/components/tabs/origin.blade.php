<div
    {{ $attributes->only('class')->merge(['class' => ($active === $tab->id ? $activeClass : '') ]) }}
    @isset($click)
        wire:click.prevent="{{ $click }}({{ $tab->id }})"
    @endisset
>
    <i class="mr-2 {{ $tab->type->icon() }}"></i>
    {{ $tab->name }}
    ({{ $tab->pendingPulls->count() }})
</div>
