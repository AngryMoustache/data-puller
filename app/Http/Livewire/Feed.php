<?php

namespace App\Http\Livewire;

use App\Enums\Status;
use App\Models\Pull;
use App\Models\Tag;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

class Feed extends Component
{
    public bool $loaded = false;

    public array $fields = [];

    public ?Pull $pull;
    public Collection $tags;

    public function mount()
    {
        if ($this->loaded) {
            $this->ready();
        }
    }

    public function ready()
    {
        $this->loaded = true;

        $this->tags = Tag::get();
        $this->nextPull();
    }

    public function render()
    {
        if (! $this->loaded) {
            return view('livewire.pre-load');
        }

        return view('livewire.feed');
    }

    public function nextPull()
    {
        $this->pull = Pull::pending()->first();

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

       $tags = collect(explode(',', $this->fields['tags']))->map(function ($tag) {
            $tag = Str::of($tag)->trim()->lower();
            return Tag::updateOrCreate([
                'name' => $tag,
                'slug' => $tag->slug(),
            ]);
       });

        $this->pull->tags()->sync($tags->pluck('id'));
        $this->pull->status = Status::from($status);
        $this->pull->save();

        $this->nextPull();
    }
}
