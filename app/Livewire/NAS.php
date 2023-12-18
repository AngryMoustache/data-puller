<?php

namespace App\Livewire;

use App\Task;
use App\Livewire\Traits\CanToast;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

class NAS extends Component
{
    use CanToast;

    public Collection $taskList;

    public function mount()
    {
        $this->fetchTasksFromNAS();
    }

    public function fetchTasksFromNAS()
    {
        $this->taskList = collect(Storage::disk('nas-media')->allFiles(
            'mobileart/tasks'
        ));
    }

    public function pull(string $path)
    {
        $task = new Task($path);
        $task->createPull();

        $folder = "mobileart/public/{$task->type->folder()}/{$task->media_id}";
        Storage::disk('nas-media')->makeDirectory($folder, 'public');
        Storage::disk('nas-media')->move(
            $path, "{$folder}/{$task->name}"
        );

        $this->toast('Item has been pulled');

        $this->taskList = $this->taskList->reject(fn ($item) => $item === $path);
    }

    public function render()
    {
        return view('livewire.nas', [
            'tasks' => $this->taskList->mapInto(Task::class),
        ]);
    }
}
