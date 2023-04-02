<?php

namespace App\Http\Livewire\Feed;

use App\Enums\Status;
use App\Http\Livewire\Traits\HasPreLoading;
use App\Models\Artist;
use App\Models\Pull;
use App\Models\Tag;
use Illuminate\Support\Collection;
use Livewire\Component;

class Show extends Component
{
    use HasPreLoading;

    public Pull $pull;

    public array $fields;

    public function mount(Pull $pull)
    {
        $this->pull = $pull;

        $this->fields = [
            'name' => $this->pull->name,
            'artist' => $this->pull->artist->name,
            'tags' => [],
        ];
    }

    public function render()
    {
        if (! $this->loaded) {
            return $this->renderLoading();
        }

        return view('livewire.feed.show', [
            'tags' => Tag::whereDoesntHave('parent')
                ->with('children.children.children.children.children')
                ->get()
        ]);
    }

    public function save(string $status)
    {
        $artist = Artist::firstOrCreate([
            'name' => $this->fields['artist'],
        ]);

        $this->pull->update([
            'name' => $this->fields['name'],
            'artist_id' => $artist->id,
            'status' => $status,
            'verdict_at' => now(),
        ]);

        // Make sure we don't save any tags where the parent is not in the list
        $all = Tag::find(collect($this->fields['tags'])->filter()->keys());
        $tags = $all->filter(fn (Tag $tag) => is_null($tag->parent_id) || $all->contains('id', $tag->parent_id));

        $this->pull->tags()->sync($tags->pluck('id'));

        if ($status !== Status::PENDING->value) {
            return redirect()->route('feed.index');
        }
    }
}
