<?php

namespace App\Livewire;

use App\Filters\Filter;
use App\Models\Artist;
use App\Models\Folder;
use App\Models\Tag;
use Livewire\Component;

class GlobalSearch extends Component
{
    public bool $isPullIndex = false;

    public function mount(null | bool $isPullIndex = null)
    {
        $this->isPullIndex = is_null($isPullIndex) ? request()->routeIs('pull.index') : $isPullIndex;
    }

    public function fetchOptions()
    {
        $tags = Tag::where('is_hidden', false)
            ->whereHas('tagGroups.pull', fn ($q) => $q->online())
            ->orderBy('long_name')
            ->get();

        $artists = Artist::whereDoesntHave('parent')
            ->whereHas('pulls', fn ($q) => $q->online())
            ->get();

        $folders = Folder::orderBy('name')
            ->whereHas('pulls', fn ($q) => $q->online())
            ->get();

        $this->dispatch(
            'options-fetched',
            collect($tags)
                ->merge($folders)
                ->merge($artists)
                ->map(fn ($item) => optional(Filter::fromModel($item))->toArray())
                ->toArray()
        );
    }
}
