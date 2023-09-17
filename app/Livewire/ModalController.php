<?php

namespace App\Livewire;

use Illuminate\Support\Arr;
use Livewire\Attributes\On;
use Livewire\Component;

class ModalController extends Component
{
    public ?string $modal = null;
    public array $params = [];

    #[On('openModal')]
    public function openModal($modal, $params = [])
    {
        $this->modal = $modal;
        $this->params = Arr::wrap($params);
    }

    #[On('closeModal')]
    public function closeModal()
    {
        $this->modal = null;
        $this->params = [];
    }
}
