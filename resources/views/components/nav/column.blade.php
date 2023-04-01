<div x-data="{
    open: false,
    toggle() {
        this.open = ! this.open
    }
}">
    <div
        class="h-screen border-border border-r mr-4 p-4 transition-all"
        x-bind:class="{
            'w-64': open,
        }"
    >
        <div class="flex flex-col gap-4">
            <span @click="toggle" class="pt-4 p-8 mb-4 -mx-4 border-border border-b">
                <x-heroicon-o-bars-3 class="w-6 h-6" />
            </span>

            <x-nav.item
                :route="route('home.index')"
                :active="request()->routeIs('home.index')"
                icon="heroicon-o-home"
                label="Home"
            />

            <x-nav.item
                :route="route('pull.index')"
                :active="request()->routeIs('pull.index')"
                icon="heroicon-o-folder"
                label="Pulls"
            />

            @if ($feed)
                <x-nav.item
                    :route="route('pull.index')"
                    :active="request()->routeIs('pull.index')"
                    icon="heroicon-o-bell-alert"
                    label="Feed"
                />
            @endif

            <x-nav.item
                :route="route('pull.index')"
                :active="request()->routeIs('pull.index')"
                icon="heroicon-o-clock"
                label="History"
            />
        </div>
    </div>
</div>
