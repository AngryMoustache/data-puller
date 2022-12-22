<?php

namespace App\Http\Livewire;

use AngryMoustache\Rambo\Http\Livewire\Crud\ResourceComponent;
use App\Enums\Status;
use App\Http\Livewire\Traits\HandleSteps;
use App\Models\Tag;
use App\Models\Pull;
use App\Rambo\Pull as RamboPull;
use Illuminate\Support\Collection;

class Feed extends ResourceComponent
{
    use HandleSteps;

    public bool $loaded = false;
    public array $fields = [];
    public ?Pull $pull = null;

    public int $maxSteps;
    public Collection $tags;

    public $listeners = [
        'changed-value' => 'fieldUpdated',
        'refreshComponent' => '$refresh',
    ];

    public function mount()
    {
        if ($this->loaded) {
            $this->ready();
        }
    }

    public function ready()
    {
        $this->tags = Tag::whereDoesntHave('parent')->get();
        $this->maxSteps = $this->tags->count() + 2;
        $this->loaded = true;

        $this->nextPull();
        $this->resource = (new RamboPull())->item($this->pull);
    }

    public function render()
    {
        if (! $this->loaded || ! $this->pull) {
            return view('livewire.pre-load');
        }

        return view('livewire.feed', [
            'mediaForm' => $this->mediaForm(),
        ]);
    }

    public function save()
    {
        $this->finish(Status::ONLINE);
    }

    public function archive()
    {
        $this->finish(Status::OFFLINE);
    }

    public function finish($status)
    {
        $this->pull->status = $status;
        $this->pull->verdict_at = now();
        $this->saveStep();
        $this->nextPull();
    }
}
