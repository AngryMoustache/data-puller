<?php

namespace App\Http\Livewire\Pull;

use App\Models\History;
use App\Models\Pull;
use Livewire\Component;

class Show extends Component
{
    public Pull $pull;

    public $listeners = [
        'refresh' => '$refresh',
    ];

    public function mount(Pull $pull)
    {
        $this->pull = $pull;

        History::add($pull);
    }
}
