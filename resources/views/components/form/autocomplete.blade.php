@props([
    'id' => Str::random(16),
    'label',
    'wireModel',
    'options',
    'placeholder' => null,
])

<div class="flex w-full items-center gap-4">
    @if ($label)
        <label class="w-32" for="{{ $id }}">
            {{ $label }}
        </label>
    @endif

    <div
        class="relative w-full"
        x-data="{
            query: @entangle($wireModel),
            searching: false,
            options: @entangle('artists'),
            highlight: -1,
            init () {
                $watch('query', (value) => { this.highlight = -1 })
            },
            filteredOptions () {
                return [...this.options]
                    .filter((option) => option.value.toLowerCase().includes(this.query.toLowerCase()))
                    .slice(0, 10)
            },
            select (option = null) {
                const options = this.filteredOptions()
                option = option || options[this.highlight] || options[0]

                if (! option) {
                    return
                }

                this.query = option.value
                this.searching = false
            }
        }"
    >
        <x-form.input
            :$id
            x-model="query"
            placeholder="{{ $placeholder }}"
            x-on:keydown.enter.prevent="select()"
            x-on:keydown.arrow-up.prevent="highlight = (highlight < 0 ? filteredOptions().length - 1 : highlight - 1)"
            x-on:keydown.arrow-down.prevent="highlight = (highlight === filteredOptions().length - 1 ? 0 : highlight + 1)"
            x-on:focus="searching = true"
            {{-- x-on:blur="searching = false" --}}
        />

        <div x-show="searching && query.length > 0 && filteredOptions().length > 0" style="display: none">
            <ul class="
                absolute z-10 w-full bg-surface rounded-xl mt-1
                overflow-hidden border border-border
            ">
                <template x-for="(option, key) in filteredOptions()" key="option.key">
                    <li
                        class="flex items-center gap-2 px-4 py-2 cursor-pointer"
                        x-bind:class="{'bg-dark': highlight === key}"
                        x-on:click.prevent="select(option)"
                        x-on:mouseenter="highlight = key"
                    >
                        <span x-text="option.value"></span>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</div>
