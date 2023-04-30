<?php

namespace App\Http\Livewire;

use App\Entities\Filter;
use App\Models\Artist;
use App\Models\Folder;
use App\Models\Tag;
use Livewire\Component;

class GlobalSearch extends Component
{
    public bool $isPullIndex = false;

    public function mount()
    {
        $this->isPullIndex = request()->routeIs('pull.index');
    }

    public function fetchOptions()
    {
        $tags = Tag::where('hidden', false)
            ->whereHas('pulls', fn ($q) => $q->online())
            ->orderBy('long_name')
            ->get();

        $artists = Artist::whereHas('pulls', fn ($q) => $q->online())
            ->get();

        $folders = Folder::orderBy('name')
            ->whereHas('pulls')
            ->get();

        $this->emit(
            'options-fetched',
            collect($tags)
                ->merge($folders)
                ->merge($artists)
                ->map(fn ($item) => Filter::fromModel($item)->toArray())
                ->toArray()
        );
    }
}
