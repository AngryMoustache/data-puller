<?php

namespace App\Http\Livewire;

use App\Models\Artist;
use App\Models\Pull;
use App\Models\Tag;
use Livewire\Component;

class GlobalSearch extends Component
{
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

        $artists = Artist::whereHas('pulls')
            ->get()
            ->map(fn ($artist) => [
                'id' => $artist->id,
                'key' => $artist->name,
                'name' => $artist->name,
                'slug' => $artist->slug,
                'type' => 'artist',
            ]);

        $this->emit(
            'options-fetched',
            collect($tags)->merge($artists)->toArray()
        );
    }
}
