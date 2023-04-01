<?php

namespace App\Http\Livewire\History;

use App\Http\Livewire\Traits\HasPreLoading;
use App\Models\History;
use Livewire\Component;

class Index extends Component
{
    use HasPreLoading;

    public function render()
    {
        if (! $this->loaded) {
            return $this->renderLoading();
        }

        $history = History::getList();

        return view('livewire.history.index', [
            'history' => $history,
        ]);
    }
}
