<?php

namespace App\Livewire\Modal;

use App\Livewire\Traits\CanToast;
use Livewire\Component;

class Modal extends Component
{
    use CanToast;

    public function placeholder()
    {
        return view('livewire.modal.loading');
    }
}
