<?php

namespace App\Http\Livewire;

use App\Models\Origin;
use Livewire\Component;

class Gallery extends Component
{
    public function mount()
    {
        $this->origins = Origin::where('online', 1)
            ->whereHas('pendingPulls')
            ->get();
    }
}
