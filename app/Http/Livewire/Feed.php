<?php

namespace App\Http\Livewire;

use App\Enums\Status;
use App\Models\Pull;
use App\Models\TagGroup;
use Illuminate\Support\Collection;
use Livewire\Component;

class Feed extends Component
{
    public bool $loaded = false;

    public array $fields = [];

    public ?Pull $pull = null;
    public Collection $tagGroups;

    protected $listeners = [
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
        $this->loaded = true;

        $this->tagGroups = TagGroup::with('tags')->get();
        $this->nextPull();
    }

    public function render()
    {
        if (! $this->loaded || ! $this->pull) {
            return view('livewire.pre-load');
        }

        return view('livewire.feed');
    }

    public function nextPull()
    {
        $this->pull = Pull::pending()->first();
        if (! $this->pull) {
            return;
        }

        $this->fields = [
            'name' => $this->pull->name,
            'artist' => $this->pull->artist,
            'tags' => $this->pull->tags->pluck('name')->join(', '),
        ];
    }

    public function save($status)
    {
        $this->pull->name = $this->fields['name'];
        $this->pull->artist = $this->fields['artist'];

       $tags = collect($this->fields['tags'])->filter()->keys();

        $this->pull->tags()->sync($tags);
        $this->pull->status = Status::from($status);
        $this->pull->save();

        $this->nextPull();
    }
}
