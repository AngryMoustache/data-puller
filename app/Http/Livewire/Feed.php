<?php

namespace App\Http\Livewire;

use App\Models\Pull;
use Illuminate\Support\Collection;
use Livewire\Component;

class Feed extends Component
{
    public Collection $pulls;

    public function mount()
    {
        $this->pulls = Pull::pending()->get();
    }
}
