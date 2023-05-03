<div class="modal">
    <div class="modal-overlay" x-on:click="window.closeModal()"></div>

    <div {{ $attributes->merge(['class' => 'modal-content']) }}>
        {{ $slot }}
    </div>
</div>
