<div>
    @if ($logs->isEmpty())
        <p class="opacity-50">No logs to display, we're all good!</p>
    @else
        <div class="flex flex-col gap-4">
            @foreach ($logs as $log)
                <div
                    class="flex flex-col items-center gap-4 px-4 py-3 border border-border rounded-lg bg-surface"
                    x-data="{ open: false }"
                >
                    <div class="flex gap-2 items-center justify-between w-full">
                        <div class="flex flex-col">
                            <x-headers.h3 class="pt-1">
                                <x-origin :origin="$log->origin" />
                                <span class="ml-2">
                                    {{ $log->message }}
                                </span>
                            </x-headers.h3>

                            <p class="pt-2">
                                Created
                                {{ $log->created_at->format('M j, Y \a\t H:i') }}
                                <span class="opacity-50 ml-1">
                                    ({{ $log->created_at->diffForHumans() }})
                                </span>
                            </p>
                        </div>

                        <div class="flex gap-2 justify-center items-center">
                            <x-form.button-secondary
                                class="flex gap-2 items-center"
                                x-on:click.prevent="open = !open"
                            >
                                <x-heroicon-o-information-circle class="w-5 h-5" />
                            </x-form.button-secondary>

                            <x-form.button
                                class="flex gap-2 items-center"
                                x-on:click.prevent="$wire.call('handle', {{ $log->id }})"
                            >
                                <x-heroicon-o-check class="w-5 h-5" />
                            </x-form.button>
                        </div>
                    </div>

                    <div
                        class="bg-background py-2 px-3 rounded-lg"
                        x-show="open"
                        x-transition
                        x-cloak
                    >
                        {{ json_encode($log->meta) }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
