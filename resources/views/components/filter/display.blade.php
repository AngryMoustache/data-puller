<li
    wire:click="changeDisplay('{{ $value->value }}')"
    @if ($current === $value)
        class="cursor-pointer text-primary"
    @else
        class="cursor-pointer transition-all hover:!text-primary"
        style="color: #111111"
    @endif
>
    <i class="{{ $icon }}"></i>
</li>
