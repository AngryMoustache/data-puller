<?php

namespace App\Http\Livewire;

use App\Enums\Display;
use App\Enums\Sorting;
use App\Http\Livewire\Wireables\FilterBag;
use Livewire\Component;

class Gallery extends Component
{
    public FilterBag $bag;

    public Sorting $sort = Sorting::POPULAR;

    public Display $display = Display::CARD;

    public $loaded = false;

    public function mount()
    {
        if ($this->loaded) {
            $this->ready();
        }
    }

    public function ready()
    {
        $this->bag = new FilterBag;
        $this->loaded = true;
    }

    public function render()
    {
        if (! $this->loaded) {
            return view('livewire.pre-load');
        }

        return view('livewire.gallery', [
            'pulls' => $this->bag->pulls()
                ->when($this->sort, fn ($items) => $this->sort->sortCollection($items)),
        ]);
    }
}
