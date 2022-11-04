<?php

namespace App\Http\Livewire;

use App\Enums\Status;
use App\Models\Origin;
use App\Models\Pull;
use App\Models\Tag;
use Illuminate\Support\Collection;
use Livewire\Component;

class Feed extends Component
{
    public Collection $origins;
    public Collection $tags;

    public ?Origin $active;
    public ?Pull $pull;

    public array $selections = [];

    public function mount()
    {
        $this->tags = Tag::whereDoesntHave('parent')->get();
        $this->origins = Origin::query()->whereHas('pendingPulls')->orderBy('name', 'asc')->get();
        $this->active = $this->origins->first();

        $this->nextPull();
    }

    public function nextPull()
    {
        if ($this->active) {
            $this->origins = Origin::query()->whereHas('pendingPulls')->orderBy('name', 'asc')->get();
            $this->active = $this->active->refresh();

            if ($this->active->pendingPulls->isEmpty()) {
                $this->active = $this->origins->first();
                $this->nextPull();
            } else {
                $this->pull = $this->active->pendingPulls->first();
                $this->selections = $this->pull->tags->mapWithKeys(fn ($tag) => [$tag->id => true])->toArray();
            }
        }
    }

    public function changeOrigin($id)
    {
        $this->active = $this->origins->where('id', $id)->first();
        $this->pull = $this->active->pendingPulls->first();
    }

    public function saveSelections()
    {
        // Filter out tags from unselected parents
        $ids = collect($this->selections)->filter()->keys()->toArray();
        $tags = Tag::whereIn('id', $ids)->with('children')->get();
        $ids += $this->tags->pluck('id')->toArray();

        $tags = $tags->filter(function ($tag) use ($ids) {
            $parent = $tag->parent;
            while ($parent) {
                if (! in_array($parent?->id, $ids)) {
                    return false;
                }

                $parent = $parent->parent;
            }

            return true;
        });


        // Save tags on the pull and move on to the next
        $this->pull->tags()->sync($tags->pluck('id')->toArray());
        $this->savePull(Status::ONLINE);
    }

    public function updateTagName($id, $name)
    {
        $tag = Tag::find($id);
        $tag->name = $name;
        $tag->save();
    }

    public function updateName($name)
    {
        $this->pull->name = $name;
        $this->pull->saveQuietly();
    }

    public function savePull($status)
    {
        $this->pull->status = $status;
        $this->pull->verdict_at = now();
        $this->pull->save();

        $this->nextPull();
    }
}
