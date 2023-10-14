<?php

namespace App\Livewire;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class ModalController extends Component
{
    public array $modals = [];

    #[On('openModal')]
    public function openModal($modal, $params = [])
    {
        $this->modals[Str::random()] = [
            'modal' => $modal,
            'params' => Arr::wrap($params),
        ];
    }

    #[On('closeModal')]
    public function closeModal()
    {
        array_pop($this->modals);
    }
}
