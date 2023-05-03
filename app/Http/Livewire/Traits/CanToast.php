<?php

namespace App\Http\Livewire\Traits;

trait CanToast
{
    public function toast($message)
    {
        $this->dispatchBrowserEvent('toast', [
            'message' => $message,
            'color' => 'primary',
        ]);
    }
}
