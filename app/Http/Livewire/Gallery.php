<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Wireables\FilterBag;
use Livewire\Component;

class Gallery extends Component
{
    public FilterBag $bag;

    public function mount($filters = null)
    {
        $this->bag = new FilterBag($filters);
    }

    public function render()
    {
        return view('livewire.gallery', [
            'pulls' => $this->bag->pulls(),
        ]);
    }
}
