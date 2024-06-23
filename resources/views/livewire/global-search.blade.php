<div
    class="relative"
    wire:init="fetchOptions"
    x-data="{
        query: '',
        options: [],
        highlight: -1,
        loading: true,
        isPullIndex: @js($isPullIndex),
        init () {
            $watch('query', (value) => { this.highlight = -1 })
        },
        setOptions (e) {
            this.options = e
            this.loading = false
        },
        filteredOptions () {
            const options = [...this.options]
                .filter((option) => this.slugify(option.value + option.key).includes(this.slugify(this.query)))
                .slice(0, 10)

            // Custom query filter at the bottom
            options.push({
                type: 'query',
                id: null,
                value: 'Search for ' + this.query,
                key: this.query,
            })

            return options
        },
        select (option = null) {
            const options = this.filteredOptions()
            option = option || options[this.highlight] || options[0]

            if (! option) {
                return
            }

            if (this.isPullIndex) {
                $wire.dispatch('toggleFilter', [option.type, option.id || option.key])
                this.query = ''
            } else {
                let type = ''

                switch (option.type.substr(option.type.lastIndexOf('\\') + 1)) {
                    case 'Tag': type = 'tags'; break;
                    case 'Artist': type = 'artists'; break;
                    case 'Folder': type = 'folders'; break;
                    default: type = 'query'; break;
                }

                window.location.href = `/pulls/${type}:${option.key}`
            }
        },
        slugify (str) {
            return String(str)
                .normalize('NFKD')
                .replace(/[\u0300-\u036f]/g, '')
                .trim()
                .toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
        }
    }"
    x-on:options-fetched="setOptions($event.detail[0] || [])"
>
    <form class="
        flex w-full border border-border rounded-xl
        hover:border-dark focus-within:border-dark
    ">
        <x-form.input
            x-model="query"
            placeholder="Search by title, tag, or author"
            class="bg-background rounded-r-none"
            x-on:keydown.enter.prevent="select()"
            x-on:keydown.arrow-up.prevent="highlight = (highlight < 0 ? filteredOptions().length - 1 : highlight - 1)"
            x-on:keydown.arrow-down.prevent="highlight = (highlight === filteredOptions().length - 1 ? 0 : highlight + 1)"
        />

        <button wire:click.prevent class="
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
                    class="flex items-center gap-2 px-4 py-2 cursor-pointer"
                    x-bind:class="{'bg-dark': highlight === key}"
                    x-on:click.prevent="select(option)"
                    x-on:mouseenter="highlight = key"
                >
                    <x-heroicon-o-tag x-show="option.type.includes('Tag')" class="w-4 h-4" />
                    <x-heroicon-o-user-group x-show="option.type.includes('Artist')" class="w-4 h-4" />
                    <x-heroicon-o-folder-open x-show="option.type.includes('Folder')" class="w-4 h-4" />
                    <x-heroicon-o-magnifying-glass x-show="option.type === 'query'" class="w-4 h-4" />

                    <span x-text="option.value"></span>
                </li>
            </template>
        </ul>
    </div>
</div>
