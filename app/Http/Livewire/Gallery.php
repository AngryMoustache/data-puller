<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Wireables\FilterBag;
use App\Models\Tag;
use Illuminate\Support\Collection;
use Livewire\Component;

class Gallery extends Component
{
    public FilterBag $bag;
    public Collection $tags;

    public function mount($filters = null)
    {
        $this->bag = new FilterBag($filters);
        $this->tags = Tag::fullTagList();
    }

    public function updateTags($selections)
    {
        $this->bag->updateTags($selections);
    }

    public function render()
    {
        return view('livewire.gallery', [
            'pulls' => $this->bag->pulls(),
        ]);
    }
}
