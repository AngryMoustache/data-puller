<?php

namespace App\Livewire\Traits;

trait CanToast
{
    public function toast($message)
    {
        $this->dispatch('toast', [
            'message' => $message,
            'color' => 'dark',
        ]);
    }
}
