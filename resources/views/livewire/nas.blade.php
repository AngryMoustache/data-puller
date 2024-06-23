<x-container class="flex">
    <table class="w-full" x-data>
        @foreach ($tasks as $task)
            <tr @class([
                'bg-surface' => $loop->odd,
            ])>
                <td class="py-2 px-4">
                    <span class="flex gap-2 items-center">
                        <x-dynamic-component :component="$task->type->icon()" class="w-4 h-4" />
                        {{ $task->type->label() }}
                    </span>
                </td>
                <td class="py-2 px-4">{{ $task->name }}</td>
                <td class="py-2 px-4">
                    <x-form.button
                        x-on:click="$wire.pull('{{ $task->path }}')"
                        text="Create pull"
                    />
                </td>
            </tr>
        @endforeach
    </table>
</x-container>
