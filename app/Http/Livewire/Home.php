<?php

namespace App\Http\Livewire;

use App\Models\Origin;
use App\Models\Pull;
use Livewire\Component;

class Home extends Component
{
    public function mount()
    {
        $this->origins = Origin::where('online', 1)->get();
        $this->highlight = Pull::online()->get()->random();
        $this->latest = Pull::online()->latest()->limit(4)->get();
        $this->popular = Pull::online()->orderByDesc('views')->limit(4)->get();
    }
}
