@props([
    'main' => $slot,
    'footer' => null,
    'disableOverlayClick' => false,
])

<div class="modal">
    <div
        class="modal-overlay"
        @if (! $disableOverlayClick)
            x-on:click="window.closeModal()"
        @endif
    ></div>

    <div class="modal-content">
        <x-surface class="!p-0">
            <div {{ $attributes->merge(['class' => 'flex flex-col gap-4 p-4']) }}>
                {{ $main }}
            </div>

            @if ($footer)
                <div class="
                    modal-footer flex w-full p-4 mt-4 gap-4 justify-end
                    border-t border-border bg-surface
                ">
                    {{ $footer }}
                </div>
            @endif
        </x-surface>
    </div>
</div>
