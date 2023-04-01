<?php

namespace App\Http\Livewire;

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
                ];
            })
            ->toArray();

        $this->emit('options-fetched', $tags);
    }
}
