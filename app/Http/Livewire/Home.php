<?php

namespace App\Http\Livewire;

use App\Facades\PullCache;
use App\Models\Pull;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        $pulls = Pull::find(PullCache::get()->keys()->random(15));

        return view('livewire.home', [
            'pulls' => $pulls,
        ]);
    }
}
