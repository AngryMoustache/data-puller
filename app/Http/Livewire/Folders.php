<?php

namespace App\Http\Livewire;

use App\Models\Folder;
use Livewire\Component;

class Folders extends Component
{
    public function mount()
    {
        $this->folders = Folder::whereHas('pulls')->get();
    }
}
