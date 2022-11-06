<?php

namespace App\Http\Livewire;

use App\Enums\Status;
use App\Models\Pull;
use App\Models\Tag;
use App\Resources\JsonTag;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

class Feed extends Component
{
    public Collection $pulls;
    public Collection $tags;
    public Collection $selections;

    public ?Pull $pull;

    public function mount()
    {
        $this->nextPull();
    }

    public function nextPull()
    {
        $this->tags = JsonTag::collection(
            Pull::get()
                ->pluck('tags')
                ->flatten()
                ->unique(fn ($item) => $item->id . $item?->pivot?->data)
                ->values()
        );

        $this->pulls = Pull::pending()->pluck('id');

        // Do not use 'return' this function
        if ($this->pulls->isNotEmpty()) {
            $this->pull = Pull::find($this->pulls)->first();

            $this->selections = JsonTag::collection(
                $this->pull->tags->concat($this->pull->suggestedTags)
            );
        }
    }

    public function saveSelections($selections)
    {
        $selections = collect($selections);

        // Save any new tags
        $selections = $selections->map(function ($selection) {
            $tag = Tag::updateOrCreate(
                ['slug' => Str::slug($selection['name'])],
                ['name' => $selection['name']]
            );

            $selection['id'] = $tag->id;
            return $selection;
        });

        // Get tags and save them with the extra data
        $selections = $selections->mapWithKeys(function ($selection) {
            return [$selection['id'] => [
                'data' => collect(explode(',', $selection['extra'] ?? ''))
                    ->map(fn ($i) => trim($i))
                    ->filter()
                    ->toJson(),
            ]];
        });

        // Save tags on the pull and move on to the next
        $this->pull->tags()->sync($selections->toArray());
        $this->savePull(Status::ONLINE);
    }

    public function updateName($name)
    {
        $this->pull->name = $name;
        $this->pull->saveQuietly();
    }

    public function updateAttachments($media)
    {
        $this->pull->attachments()->sync(collect($media)->mapWithKeys(function ($item, $key) {
            return [$item['id'] => ['sort_order' => 1000 + $key]];
        })->toArray());

        $this->pull = $this->pull->refresh();
    }

    public function savePull($status)
    {
        $this->pull->status = $status;
        $this->pull->verdict_at = now();
        $this->pull->save();

        $this->nextPull();
    }
}
