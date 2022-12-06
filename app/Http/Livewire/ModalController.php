<?php

namespace App\Http\Livewire;

use Illuminate\Support\Arr;
use Livewire\Component;

class ModalController extends Component
{
    public ?string $modal = null;
    public array $params = [];

    protected $listeners = [
        'openModal',
        'closeModal',
    ];

    public function openModal($modal, $params)
    {
        $this->modal = $modal;
        $this->params = Arr::wrap($params);
    }

    public function closeModal()
    {
        $this->modal = null;
        $this->params = [];
    }
}
