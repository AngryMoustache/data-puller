<?php

namespace App\Http\Livewire;

use App\Models\Pull;
use Livewire\Component;

class PullDetail extends Component
{
    public Pull $pull;

    public function mount(Pull $pull)
    {
        $this->pull = $pull;
        $this->pull->increment('views');
    }
}
