<?php

namespace App\View\Components\Nav;

use App\Models\Pull;
use Illuminate\View\Component;

class Column extends Component
{
    public int $feed;

    public function __construct()
    {
        $this->feed = Pull::pending()->count();
    }

    public function render()
    {
        return view('components.nav.column');
    }
}
