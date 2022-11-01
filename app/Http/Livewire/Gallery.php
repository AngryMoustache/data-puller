<?php

namespace App\Http\Livewire;

use App\Models\Pull;
use Illuminate\Support\Collection;
use Livewire\Component;

class Gallery extends Component
{
    public Pull $latest;
    public Collection $pulls;

    public function mount()
    {
        $pulls = Pull::orderBy('id', 'desc')->get();
        // $pulls = Pull::online()->orderBy('id', 'desc')->get();

        $this->pulls = $pulls->skip(1);
        $this->latest = $pulls->first();
    }
}
