<?php

namespace App\Livewire\Feed;

use App\Models\Attachment;
use Api\Jobs\RebuildCache;
use App\Enums\Status;
use App\Livewire\Traits\CanToast;
use App\Livewire\Traits\HasPreLoading;
use App\Models\Artist;
use App\Models\Pull;
use App\Models\Tag;
use App\Models\TagGroup;
use App\Models\Video;
use App\PullMedia;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    use HasPreLoading;
    use CanToast;

    public Pull $pull;
    public array $fields;
    public array $media;

    public function mount(Pull $pull)
    {
        // Check if the pull has a main tag group
        TagGroup::updateOrCreate([
            'pull_id' => $pull->id,
            'name' => 'Main tags',
            'is_main' => true,
        ]);

        $this->pull = $pull->refresh();
        $this->media = $this->pull->media->map->toJson()->toArray();

        $this->fields = [
            'name' => $this->pull->name,
            'artist' => $this->pull->artist?->name ?? 'Unknown',
            'sourceUrl' => $this->pull->source_url,
            'thumbnails' => collect($this->pull->thumbnails ?? [])->values(),
            'tagGroups' => $this->pull->tagGroups->map(fn (TagGroup $tagGroup) => [
                'id' => $tagGroup->id,
                'pull_id' => $tagGroup->pull_id,
                'name' => $tagGroup->name,
                'is_main' => $tagGroup->is_main,
                'tags' => $tagGroup->tags
                    ->pluck('id')
                    ->mapWithKeys(fn (int $id) => [$id => true])
                    ->toArray(),
            ])->toArray(),
        ];
    }

    public function render()
    {
        app('site')->title($this->pull->name);

        if (! $this->loaded) {
            return $this->renderLoading();
        }

        $this->pull = $this->pull->fresh(['attachments']);

        return view('livewire.feed.show');
    }

    public function save(string $status)
    {
        $this->saveMedia();

        $this->pull->update([
            'name' => $this->fields['name'],
            'status' => $status,
            'source_url' => $this->fields['sourceUrl'],
            'thumbnails' => collect($this->fields['thumbnails'])
                ->reject(fn (array $thumbnail) => isset($thumbnail['deleted']))
                ->toArray(),
            'verdict_at' => $this->pull->verdict_at ?? now(),
            'artist_id' => Artist::firstOrCreate([
                'name' => $this->fields['artist'],
                'slug' => Str::slug($this->fields['artist']),
            ])->id,
        ]);

        $this->saveTags();

        RebuildCache::dispatch();

        if ($status !== Status::PENDING->value) {
            return redirect()->route('feed.index');
        } else {
            $this->toast('Pull saved with the pending status');
        }
    }

    #[On('set-media')]
    public function setMedia(array $selections)
    {
        $this->media = collect($selections)->map(function (string $id) {
            [$class, $id] = explode(':', $id);

            return $this->toJson($class::find($id));
        })->toArray();

        $this->dispatch('close-modal');

        $this->dispatch('update-media-list', $this->media);
    }

    #[On('add-thumbnail')]
    public function addThumbnail(array $selection)
    {
        $selection = Str::after(
            $selection[0] ?? $selection['id'] ?? $selection,
            ':'
        );

        $this->fields['thumbnails'][] = [
            'attachment_id' => $selection,
            'thumbnail_url' => Attachment::find($selection)->format('thumb'),
            'is_main' => count($this->fields['thumbnails']) === 0,
            'tags' => [],
        ];

        $this->dispatch('close-modal');
    }

    #[On('updated-tag-group')]
    public function updatedTagGroup(array $params)
    {
        $this->fields['tagGroups'][$params['groupKey']] = $params['group'];
    }

    #[On('updated-thumbnail-list')]
    public function updatedThumbnailList(array $params)
    {
        $this->fields['thumbnails'][$params['thumbnailKey']] = $params['thumbnail'];
    }

    public function generateName()
    {
        $tags = Tag::find(collect($this->fields['tags'])->filter()->keys());

        $this->fields['name'] = Pull::getAiName($tags);
    }

    public function createGroup()
    {
        return TagGroup::create([
            'name' => 'New group ' . count($this->fields['tagGroups']),
            'pull_id' => $this->pull->id,
            'is_main' => false,
        ]);
    }

    private function toJson(Attachment | Video $media)
    {
        return (new PullMedia($media))->toJson();
    }

    private function saveMedia()
    {
        $media = collect($this->media)
            ->map(fn (array $media, int $key) => [
                'id' => (int) Str::after($media['id'], ':'),
                'class' => (string) Str::before($media['id'], ':'),
                'sort_order' => $key + 1000,
            ])
            ->groupBy('class');

        $attachments = Collection::wrap($media[Attachment::class] ?? []);
        $videos = Collection::wrap($media[Video::class] ?? []);

        $this->pull->attachments()->sync($attachments->mapWithKeys(fn (array $media) => [
            $media['id'] => ['sort_order' => $media['sort_order']],
        ]));

        $this->pull->videos()->sync($videos->mapWithKeys(fn (array $media) => [
            $media['id'] => ['sort_order' => $media['sort_order']],
        ]));

        return $media;
    }

    private function saveTags()
    {
        foreach ($this->fields['tagGroups'] as $group) {
            // Delete group if it is marked as deleted
            if (isset($group['deleted']) && $group['deleted']) {
                TagGroup::find($group['id'])->delete();

                continue;
            }

            // Create new group if there is no ID
            if (! isset($group['id'])) {
                $tagGroup = TagGroup::create([
                    'pull_id' => $this->pull->id,
                    'name' => $group['name'],
                    'is_main' => $group['is_main'],
                ]);
            } else {
                $tagGroup = TagGroup::find($group['id']);
                $tagGroup->update([
                    'name' => $group['name'],
                    'is_main' => $group['is_main'],
                ]);
            }

            $tagGroup->tags()->sync(collect($group['tags'])->filter()->keys()->mapWithKeys(fn (int $tag) => [
                $tag => ['tag_group_id' => $tagGroup->id],
            ]));
        }
    }
}
