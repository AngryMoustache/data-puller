<div>
    <x-header>
        <x-headers.h1>Welcome back!</x-headers.h1>
    </x-header>

    @if ($pulls->isNotEmpty())
        <x-container class="pt-0 p-4">
            <div class="mb-4 relative" x-data="{
                query: '',
                tags: @js($tags),
                selections: @js($bag->tags()),
                filteredTags () {
                    return this.tags
                        .filter((tag) => tag.name.toLowerCase().includes(this.query.toLowerCase()))
                        .filter((tag) => ! this.selections.includes(tag))
                        .splice(0, 20)
                },
                autoSelect () {
                    if (this.filteredTags().length >= 1) {
                        this.toggleTag(this.filteredTags()[0])
                    }
                },
                toggleTag (tag) {
                    if (this.selections.includes(tag)) {
                        this.selections = this.selections.filter((i) => i !== tag)
                    } else {
                        this.selections.push(tag)
                        this.query = ''
                    }

                    $wire.updateTags(this.selections)
                }
            }">
                <x-form.input
                    class="!rounded-lg shadow-sm relative z-10"
                    x-model="query"
                    x-on:keydown.enter="autoSelect()"
                    placeholder="Search for a tag to filter on"
                />

                <div
                    x-show="query.length > 0"
                    class="flex absolute z-0 -top-1 -left-1 -right-1 bg-white rounded-lg shadow-lg p-4 pt-16"
                >
                    <ul class="flex flex-wrap gap-2">
                        <template x-for="tag in filteredTags()">
                            <li :key="tag.id + extra">
                                <span
                                    x-on:click="toggleTag(tag)"
                                    class="
                                        inline-block px-3 py-1 rounded-lg text-secondary flex items-center
                                        bg-white border border-secondary
                                        hover:bg-secondary hover:text-white
                                        cursor-pointer text-lg
                                        gap-1
                                    "
                                >
                                    <i class="fa fa-tag text-sm text-black-800 mr-2"></i>
                                    <span x-text="tag.name"></span>
                                    <span x-show="tag.extra !== ''" x-text="'- ' + tag.extra"></span>
                                </span>
                            </li>
                        </template>
                    </ul>
                </div>

                <div
                    x-show="selections.length > 0"
                    class="flex pt-4"
                >
                    <ul class="flex flex-wrap gap-2">
                        <template x-for="tag in selections">
                            <li :key="tag.id + extra">
                                <span
                                    x-on:click="toggleTag(tag)"
                                    class="
                                        inline-block px-3 py-1 rounded-lg text-secondary flex items-center
                                        border border-secondary
                                        hover:bg-secondary hover:text-white
                                        cursor-pointer text-lg
                                        gap-1
                                    "
                                >
                                    <i class="fa fa-tag text-sm text-black-800 mr-2"></i>
                                    <span x-text="tag.name"></span>
                                    <span x-show="tag.extra !== ''" x-text="'- ' + tag.extra"></span>
                                </span>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            <x-loading-card>
                <x-grids.pulls :$pulls />
            </x-loading-card>
        </x-container>
    @endif
</div>
