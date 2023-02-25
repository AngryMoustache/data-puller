@props([
    'label' => null,
])

<div
    class="relative z-10 w-full"
    x-data="{
        input: null,
        activated: false,
        init () {
            this.input = this.$el.querySelector('[data-label-target]')
            this.setLabelStatus()
        },
        setLabelStatus (activated = null) {
            this.value = this.input.value
            this.activated = (activated === null) ? !! this.input.value : activated
        }
    }"
>
    @if ($label)
        <label
            class="absolute top-0.5 left-0 pointer-events-none transition-all px-4 py-2"
            :class="{ '!-top-4 text-xs' : activated, 'opacity-50': ! activated }"
        >
            {{ $label }}
        </label>
    @endif

    {{ $slot }}
</div>
