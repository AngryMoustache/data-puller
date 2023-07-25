<?php

namespace App\Http\Livewire\Feed;

use AngryMoustache\Media\Models\Attachment;
use Api\Jobs\RebuildCache;
use App\Enums\Status;
use App\Http\Livewire\Traits\CanToast;
use App\Http\Livewire\Traits\HasPreLoading;
use App\Models\Artist;
use App\Models\Pull;
use App\Models\Tag;
use App\Models\Video;
use App\PullMedia;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

class Show extends Component
{
    use HasPreLoading;
    use CanToast;

    public Pull $pull;

    public array $fields;

    public array $media;

    public null | string $thumbnail = null;

    public $listeners = [
        'set-media' => 'setMedia',
    ];

    public function mount(Pull $pull)
    {
        $this->pull = $pull;

        $this->fields = [
            'name' => $this->pull->name,
            'artist' => $this->pull->artist?->name ?? 'Unknown',
            'source_url' => $this->pull->source_url,
            'tags' => $this->pull->tags->pluck('id')->mapWithKeys(fn (int $id) => [$id => true])->toArray(),
        ];

        $this->media = $this->pull
            ->media->map->toJson()
            ->toArray();

        $this->thumbnail = collect($this->media)->filter->is_thumbnail->first()['id'] ?? null;
    }

    public function render()
    {
        app('site')->title($this->pull->name);

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
        // Save the media
        $media = collect($this->media)->map(fn (array $media, int $key) => [
            'id' => (int) Str::after($media['id'], ':'),
            'class' => (string) Str::before($media['id'], ':'),
            'sort_order' => $key + 1000,
            'is_thumbnail' => (bool) ($media['id'] === $this->thumbnail),
        ])->groupBy('class');

        $attachments = Collection::wrap($media[Attachment::class] ?? []);
        $videos = Collection::wrap($media[Video::class] ?? []);

        $this->pull->attachments()->sync($attachments->mapWithKeys(fn (array $media) => [
            $media['id'] => [
                'sort_order' => $media['sort_order'],
                'is_thumbnail' => $media['is_thumbnail'],
            ],
        ]));

        $this->pull->videos()->sync($videos->mapWithKeys(fn (array $media) => [
            $media['id'] => [
                'sort_order' => $media['sort_order'],
                'is_thumbnail' => $media['is_thumbnail'],
            ],
        ]));

        $artist = Artist::firstOrCreate([
            'name' => $this->fields['artist'],
            'slug' => Str::slug($this->fields['artist']),
        ]);

        $this->pull->update([
            'name' => $this->fields['name'],
            'artist_id' => $artist->id,
            'source_url' => $this->fields['source_url'],
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
        } else {
            $this->toast('Pull saved with the pending status');
        }
    }

    public function setMedia(array $selections)
    {
        $this->media = collect($selections)->map(function (string $id) {
            [$class, $id] = explode(':', $id);

            return $this->toJson($class::find($id));
        })->toArray();

        $this->dispatchBrowserEvent('close-modal');

        $this->emit('update-media-list', $this->media);
    }

    public function generateName()
    {
        $tags =  Tag::find(collect($this->fields['tags'])->filter()->keys());

        $this->fields['name'] = Pull::getAiName($tags);
    }

    private function toJson(Attachment | Video $media)
    {
        return (new PullMedia($media))->toJson();
    }
}
