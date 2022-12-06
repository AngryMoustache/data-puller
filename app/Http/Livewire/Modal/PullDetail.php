<?php

namespace App\Http\Livewire\Modal;

use App\Models\Pull;
use Livewire\Component;

class PullDetail extends Component
{
    public Pull $Pull;

    public function mount($params)
    {
        $this->pull = Pull::find($params[0]);
        $this->pull->views++;
        $this->pull->saveQuietly();
    }
}
