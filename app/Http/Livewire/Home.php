<?php

namespace App\Http\Livewire;

use App\Models\Origin;
use App\Models\Pull;
use Livewire\Component;

class Home extends Component
{
    public function mount()
    {
        $this->origins = Origin::where('online', 1)
            ->whereHas('pendingPulls')
            ->get();

        $this->highlight = Pull::get()->random();

        $this->latest = Pull::latest()->limit(4)->get();
        $this->popular = Pull::orderByDesc('views')->limit(4)->get();
    }
}
