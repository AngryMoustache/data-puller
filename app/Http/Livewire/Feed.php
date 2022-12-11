<?php

namespace App\Http\Livewire;

use App\Enums\Status;
use App\Models\Folder;
use App\Models\Pull;
use Illuminate\Support\Collection;
use Livewire\Component;

class Feed extends Component
{
    public bool $loaded = false;

    public array $fields = [];
    public Collection $folders;

    public ?Pull $pull = null;

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
        $this->folders = Folder::get();

        $this->loaded = true;
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
        ];
    }

    public function save($status)
    {
        $folders = collect($this->fields['folders'])->filter()->keys();
        $this->pull->folders()->sync($folders);

        $this->pull->name = $this->fields['name'];
        $this->pull->artist = $this->fields['artist'];
        $this->pull->status = Status::from($status);
        $this->pull->verdict_at = now();
        $this->pull->save();

        $this->nextPull();
    }
}
