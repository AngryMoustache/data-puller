<div>
    <x-header>
        <x-headers.h1>Welcome back!</x-headers.h1>
    </x-header>

    <x-container class="pt-0 p-4">
        <div class="mb-4 relative" x-data="{
            query: '',
            tags: @js($tags),
            selected: @js($bag->tags()),
            filteredTags () {
                const query = this.normalize(this.query)

                return this.tags
                    .filter(tag => this.normalize(tag.fullSlug).includes(query))
                    .splice(0, 20)
            },
            autoSelect () {
                const tag = this.filteredTags()[0] || null
                if (tag) this.toggleTag(tag)
            },
            toggleTag (tag) {
                $wire.toggleTag(tag)
                this.query = ''
            },
            normalize (string) {
                return string.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            },
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
                    <template x-for="tag in filteredTags()" :key="'tag-tags-' + tag.id + tag.fullSlug">
                        <x-alpine.tag-select alpine-key="tags" />
                    </template>
                </ul>
            </div>

            @if ($bag->tags()->isNotEmpty())
                <div class="flex pt-4">
                    <ul class="flex flex-wrap gap-2">
                        <template x-for="tag in selected" :key="'tag-selected-' + tag.id + tag.fullSlug">
                            <x-alpine.tag-select alpine-key="selected" />
                        </template>
                    </ul>
                </div>
            @endif
        </div>

        <x-loading-card>
            @if ($pulls->isNotEmpty())
                <x-grids.pulls :$pulls />
            @endif
        </x-loading-card>
    </x-container>
</div>
