<div
    class="flex flex-col transition-all duration-500 ease-in-out relative"
    x-data="{
        ratings: @entangle('ratings').live,
    }"
>
    <div class="flex flex-col gap-12">
        <div class="flex flex-col gap-4">
            @foreach ($categories as $category)
                <x-headers.h2
                    class="!font-normal -mb-2"
                    :text="$category->name"
                />

                <x-form.rating
                    :category="$category"
                    :options="range(0, 10)"
                />
            @endforeach
        </div>
    </div>
</div>
