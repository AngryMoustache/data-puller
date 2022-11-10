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

    public function toggleTag($tag)
    {
        $this->bag->toggleTag($tag);
    }

    public function render()
    {
        $this->dispatchBrowserEvent(
            'update-browser-url',
            route('gallery.filter', $this->bag->toQueryString())
        );

        return view('livewire.gallery', [
            'pulls' => $this->bag->pulls(),
        ]);
    }
}
