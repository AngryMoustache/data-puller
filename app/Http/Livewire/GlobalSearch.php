<?php

namespace App\Http\Livewire;

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
            ->orderBy('long_name')
            ->get()
            ->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'key' => $tag->name,
                    'name' => $tag->long_name,
                    'slug' => $tag->slug,
                    'type' => 'tag',
                ];
            });

        $artists = Artist::with('pulls')
            ->whereHas('pulls', fn ($q) => $q->online())
            ->get()
            ->map(fn ($artist) => [
                'id' => $artist->id,
                'key' => $artist->name,
                'name' => $artist->name,
                'slug' => $artist->slug,
                'type' => 'artist',
            ]);

        $folders = Folder::orderBy('name')
            ->whereHas('pulls')
            ->get()
            ->map(fn ($folder) => [
                'id' => $folder->id,
                'key' => $folder->name,
                'name' => $folder->name,
                'slug' => $folder->slug,
                'type' => 'folders',
            ]);

        $this->emit(
            'options-fetched',
            collect($tags)->merge($folders)->merge($artists)->toArray()
        );
    }
}
