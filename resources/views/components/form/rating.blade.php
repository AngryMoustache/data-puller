@props([
    'category',
    'label' => null,
    'options' => collect(),
    'increments' => 100 / count($options),
])

<div class="relative flex gap-4 justify-between items-center rounded-full">
    <div class="absolute bg-dark rounded-full z-10 h-12" x-bind:style="{
        width: (ratings[{{ $category->id }}] * {{ $increments }}) + 10 + '%',
        transition: 'width 0.5s',
    }"></div>

    <div class="flex w-full z-20">
        @foreach ($options as $key => $value)
            <label
                class="inline-flex items-center"
                style="width: {{ $increments }}%"
            >
                <input
                    type="radio"
                    value="{{ $key }}"
                    x-model="ratings[{{ $category->id }}]"
                    class="hidden"
                >

                <span
                    x-bind:style="{
                        transition: 'all 0.5s',
                        color: {{ $key }} <= ratings[{{ $category->id }}]
                            ? 'white'
                            : 'var(--color-border)',
                        opacity: {{ $key }} == ratings[{{ $category->id }}] ? 1 : .3,
                    }"
                    class="
                        cursor-pointer
                        rounded-full h-12 w-full flex items-center justify-center
                        text-center text-xl
                    "
                >
                    {{ $value }}
                </span>
            </label>
        @endforeach
    </div>
</div>
