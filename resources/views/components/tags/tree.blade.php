@php
    $classes = 'ml-6 mb-2 flex flex-col gap-4';
@endphp

<ul {{ $attributes->only('class')->merge(['class' => $classes]) }}>
    @foreach ($tags as $tag)
        @php $children = $tag->children; @endphp

        <li class="w-full text-lg flex gap-4 items-center" x-data="{
            editing: false,
            tagName: @js($tag->name),
            newTagName: @js($tag->name),
            updateName () {
                $wire.updateTagName({{ $tag->id }}, this.newTagName)
                this.tagName = this.newTagName
                this.editing = false
            }
        }">
            <input
                type="checkbox"
                id="tag-{{ $tag->id }}"
                value="{{ $tag->id }}"
                class="w-5 h-5"
                x-model="selections[{{ $tag->id }}]"
            />

            <div class="w-full flex justify-between items-center">
                <label
                    for="tag-{{ $tag->id }}"
                    class="flex-grow mr-4"
                >
                    <template x-if="editing === true">
                        <x-form.input
                            type="text"
                            class="w-full focus:outline-none py-2"
                            x-model="newTagName"
                            x-on:keydown.enter="updateName()"
                            x-on:keydown.escape="editing = false"
                        />
                    </template>

                    <template x-if="editing === false">
                        <span>
                            <span x-text="tagName"></span>

                            @if ($children->count())
                                ({{ $children->count() }})
                            @endif
                        </span>
                    </template>
                </label>

                <span class="flex gap-1 pr-2" x-show="editing === false">
                    {{-- Edit --}}
                    <i
                        class="fa fa-pencil cursor-pointer opacity-50 hover:opacity-100 p-2"
                        x-on:click.prevent="editing = true"
                    ></i>

                    {{-- Add extra --}}
                    <i
                        class="fas fa-tag cursor-pointer opacity-50 hover:opacity-100 p-2"
                        x-on:click.prevent="openModal('addExtraToTagModal', {{ $tag->id }})"
                    ></i>

                    {{-- Add new --}}
                    <i
                        class="fas fa-plus cursor-pointer opacity-50 hover:opacity-100 p-2"
                        x-on:click.prevent="openModal('newTagOnModal', {{ $tag->id }})"
                    ></i>
                </span>

                <span class="flex gap-1 pr-2" x-show="editing === true">
                    {{-- Delete --}}
                    <i
                        class="fa fa-times cursor-pointer opacity-50 hover:opacity-100 p-2"
                        x-on:click.prevent="editing = false"
                    ></i>

                    <i
                        class="far fa-save cursor-pointer opacity-50 hover:opacity-100 p-2"
                        x-on:click.prevent="updateName()"
                    ></i>
                </span>
            </div>
        </li>

        @if ($children->count())
            <li x-show="selections[{{ $tag->id }}]" x-transition>
                <x-tags.tree
                    :tags="$children"
                    {{ $attributes->only('class')->merge(['class' => $classes . ' mb-2']) }}
                />
            </li>
        @endif
    @endforeach
</ul>
