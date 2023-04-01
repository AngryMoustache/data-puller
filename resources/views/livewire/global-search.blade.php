<div
    class="relative"
    wire:init="fetchOptions"
    x-data="{
        query: '',
        options: [],
        highlight: -1,
        init () {
            $watch('query', (value) => { this.highlight = -1 })
            $wire.on('options-fetched', (e) => { this.options = e })
        },
        filteredOptions () {
            return this.options
                .filter((option) => option.key.toLowerCase().includes(this.query.toLowerCase()))
                .slice(0, 10)
        },
        select (id = null) {
            const option = id || this.filteredOptions()[this.highlight]

            window.location.href = `/pulls/tags:${option.slug}`
        }
    }"
>
    <form class="
        flex w-full border border-border rounded-xl
        hover:border-dark focus-within:border-dark
    ">
        <x-form.input
            x-model="query"
            placeholder="Search"
            class="rounded-r-none"
            x-on:keydown.enter.prevent="select()"
            x-on:keydown.arrow-up.prevent="highlight = (highlight === 0 ? filteredOptions().length - 1 : highlight - 1)"
            x-on:keydown.arrow-down.prevent="highlight = (highlight === filteredOptions().length - 1 ? 0 : highlight + 1)"
        />

        <button class="
            flex items-center bg-surface px-3 rounded-r-xl
            hover:bg-dark
        ">
            <x-heroicon-o-magnifying-glass class="w-6 h-6" />
        </button>
    </form>

    <div x-show="query !== '' && filteredOptions().length > 0" style="display: none">
        <ul class="
            absolute z-10 w-full bg-surface rounded-xl mt-1
            overflow-hidden border border-border
        ">
            <template x-for="(option, key) in filteredOptions()" key="option.id">
                <li
                    x-text="option.name"
                    class="px-4 py-2 cursor-pointer"
                    x-bind:class="{'bg-dark': highlight === key}"
                    x-on:click.prevent="select(option.id)"
                    x-on:mouseenter="highlight = key"
                ></li>
            </template>
        </ul>
    </div>
</div>
