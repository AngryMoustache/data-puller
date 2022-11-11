<x-container>
    <div class="flex py-8">
        <div class="w-3/5 flex flex-col justify-center gap-16">
            <x-headers.h2
                text="Your personal gallery"
                class="!text-6xl"
            />

            <p class="text-2xl">
                You have pulled <x-highlight text="512" /> artworks<br>
                From <x-highlight :text="$origins->count()" /> different places
            </p>

            <div class="flex gap-4">
                <x-form.button label="Full gallery" :href="route('gallery.index')" />
                <x-form.button-secondary label="Pull more content" :href="route('feed.index')" />
            </div>
        </div>

        <div class="w-2/5">
            <x-card.pull :pull="$highlight" />
        </div>
    </div>

    <div class="flex flex-col gap-32 py-32">
        <x-section title="Latest pulls" :route="route('gallery.index')">
            <x-grid.pulls-small :pulls="$latest" />
        </x-section>

        <x-section title="Popular pulls" :route="route('gallery.index')">
            <x-grid.pulls-small :pulls="$popular" />
        </x-section>
    </div>
</x-container>
