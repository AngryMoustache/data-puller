<div x-data="{
    paused: false,
    images: @js($images),
    current: 0,
    speed: 5000,
    srcs: [],
    timeout: null,
    init () {
        this.newSrcs()
        if (this.images.length <= 1) return
        this.loop()
    },
    newSrcs () {
        this.srcs = [
            this.images[this.current],
            this.images[this.current + 1] || this.images[0],
        ]
    },
    loop () {
        this.current++
        if (this.current >= this.images.length) {
            this.current = 0
        }

        if (this.timeout) window.clearTimeout(this.timeout)
        this.timeout = window.setTimeout(() => {
            if (this.paused) return

            this.newSrcs()
            this.loop()
        }, this.speed)
    },
}">
    <template x-for="(src, key) in srcs" x-key="key">
        <div
            x-on:click="(paused = ! paused) || newSrcs() || loop()"
            class="absolute inset-0 bg-contain bg-center bg-no-repeat transition-all"
            x-bind:style="{
                backgroundImage: `url('${src}')`,
                opacity: (key === 1 ? 0 : 1),
            }"
        ></div>
    </template>

    <div class="absolute top-0 left-0 bg-primary opacity-0 hover:opacity-100">
        <button x-on:click="speed = Math.max(speed - 1000, 0)">-</button>
        <span x-text="speed"></span>
        <button x-on:click="speed += 1000">+</button>
        <br>
        <button x-on:click="newSrcs() || loop()">next</button>
    </div>

    <!-- <div class="absolute bottom-0 left-0 right-0 p-1 bg-primary transition-all">
        <div
            class="absolute inset-0 bg-surface"
            x-bind:style="{ left: ((speed / 10000) * timer) + '%' }"
        ></div>
    </div> -->
</div>
