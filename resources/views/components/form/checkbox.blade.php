@php $id ??= md5(rand(0, 10000000000)); @endphp
<div class="relative flex align-center">
    <input type="checkbox" {{ $attributes->except('class') }} id="{{ $id }}">
    <label
         for="{{ $id }}"
        {{ $attributes->only('class')->merge([
            'class' => 'checkbox-label cursor-pointer text-lg',
        ]) }}
    >
        {{ $label }}
    </label>
</div>
