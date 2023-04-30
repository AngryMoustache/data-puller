<?php

namespace App\Http\Livewire\Feed;

use Api\Jobs\RebuildCache;
use App\Enums\Status;
use App\Http\Livewire\Traits\HasPreLoading;
use App\Models\Artist;
use App\Models\Pull;
use App\Models\Tag;
use Illuminate\Support\Str;
use Livewire\Component;

class Show extends Component
{
    use HasPreLoading;

    public Pull $pull;

    public array $fields;

    public $listeners = [
        'add-attachments' => 'addAttachments',
        'refresh' => '$refresh',
    ];

    public function mount(Pull $pull)
    {
        $this->pull = $pull;

        $this->fields = [
            'name' => $this->pull->name,
            'artist' => $this->pull->artist?->name ?? 'Unknown',
            'tags' => $this->pull->tags->pluck('id')->mapWithKeys(fn (int $id) => [$id => true])->toArray(),
        ];
    }

    public function render()
    {
        if (! $this->loaded) {
            return $this->renderLoading();
        }

        $this->pull = $this->pull->fresh(['attachments']);

        return view('livewire.feed.show', [
            'attachments' => $this->pull->attachments->sortBy('sort_order'),
            'tags' => Tag::whereDoesntHave('parent')
                ->with('children.children.children.children.children')
                ->get()
        ]);
    }

    public function save(string $status)
    {
        $artist = Artist::firstOrCreate([
            'name' => $this->fields['artist'],
            'slug' => Str::slug($this->fields['artist']),
        ]);

        $this->pull->update([
            'name' => $this->fields['name'],
            'artist_id' => $artist->id,
            'status' => $status,
            'verdict_at' => $this->pull->verdict_at ?? now(),
        ]);

        // Make sure we don't save any tags where the parent is not in the list
        $all = Tag::find(collect($this->fields['tags'])->filter()->keys());
        $tags = $all->filter(fn (Tag $tag) => is_null($tag->parent_id) || $all->contains('id', $tag->parent_id));

        $this->pull->tags()->sync($tags->pluck('id'));

        // Rebuild cache
        RebuildCache::dispatch();

        if ($status !== Status::PENDING->value) {
            return redirect()->route('feed.index');
        }
    }

    public function updateMediaOrder(array $attachments)
    {
        $attachments = collect($attachments)->mapWithKeys(function (array $attachment) {
            return [$attachment['value'] => [
                'sort_order' => $attachment['order'] + 1000,
            ]];
        });

        $this->pull->attachments()->sync($attachments);

        $this->emitSelf('refreshComponent');
    }

    public function removeAttachment(int $id)
    {
        $this->pull->attachments()->detach($id);
    }

    public function addAttachments(array $selections)
    {
        $sort = $this->pull->attachments()->pluck('sort_order')->max() ?? 1000;

        $selections = collect($selections)->mapWithKeys(function (string $id, $key) use ($sort) {
            return [$id => [
                'sort_order' => $key + $sort,
            ]];
        });

        $this->pull->attachments()->syncWithoutDetaching($selections);

        $this->dispatchBrowserEvent('close-modal');
    }
}
