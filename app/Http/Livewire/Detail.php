<?php

namespace App\Http\Livewire;

use App\Models\Pull;
use Livewire\Component;

class Detail extends Component
{
    public Pull $pull;

    public function mount(Pull $pull)
    {
        $this->pull = $pull;

        $pull->increment('views');
    }
}
