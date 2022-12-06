<div class="modal">
    <div class="modal-overlay" wire:click="$emit('closeModal')"></div>

    <div {{ $attributes->merge(['class' => 'modal-content']) }}>
        {{ $slot }}
    </div>
</div>
