<div
    {{ $attributes->only('class')->merge(['class' => 'modal']) }}
    :class="{ 'modal-open': open, 'modal-closed': ! open }"
>
    <x-container>
        <x-card>
            @isset($header)
                <div class="modal-header">
                    {{ $header }}
                </div>
            @endisset

            @isset($slot)
                <div class="modal-content">
                    {{ $slot }}
                </div>
            @endisset

            @isset($footer)
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endisset
        </x-card>
    </x-container>
</div>
