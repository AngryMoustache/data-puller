<div {{ $attributes->except('items')->merge([
    'class' => 'flex gap-2'
]) }}>
    @foreach ($items as $item)
        <a
            href="{{ $item->route }}"
            class="
                relative
                px-8 bg-slate-100 rounded-t-lg
                mt-2 py-2
                hover:pb-4 hover:mt-0
                transition-all

                @if ($item->active) pb-4 mt-0 @endif
                @if ($item->notification) notification @endif
            "
        >
            {{ $item->label }}
        </a>
    @endforeach
</div>
