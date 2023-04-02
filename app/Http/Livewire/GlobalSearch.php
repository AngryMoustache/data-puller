<?php

namespace App\Http\Livewire;

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
                    'type' => 'tag',
                ];
            });

        // $artists = Pull::online()
        //     ->pluck('artist')
        //     ->filter()
        //     ->unique()
        //     ->map(fn ($artist) => [
        //         'id' => $artist,
        //         'key' => $artist,
        //         'name' => $artist,
        //         'type' => 'artist',
        //     ]);

        $this->emit(
            'options-fetched',
            collect($tags)
                // ->merge($artists)
                ->toArray()
        );
    }
}
