<?php

namespace App\Http\Livewire;

use App\Enums\Display;
use App\Http\Livewire\Wireables\FilterBag;
use Livewire\Component;

class Gallery extends Component
{
    public FilterBag $bag;

    public function mount()
    {
        $this->bag = new FilterBag;
    }

    public function changeDisplay(string $display)
    {
        $this->bag->display = Display::from($display);
    }
}
