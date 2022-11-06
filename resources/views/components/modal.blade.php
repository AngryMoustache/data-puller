<div class="modal">
    <x-container>
        <x-card {{ $attributes->only('class') }}>
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
