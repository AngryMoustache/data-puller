<?php

namespace App\Livewire\Settings;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        app('site')->title('Settings');

        return view('livewire.settings.index');
    }
}
