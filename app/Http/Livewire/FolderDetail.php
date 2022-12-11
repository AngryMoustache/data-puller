<?php

namespace App\Http\Livewire;

use App\Enums\Display;
use App\Models\Folder;
use Livewire\Component;

class FolderDetail extends Component
{
    public Folder $folder;

    public Display $display = Display::COMPACT;

    public function mount(Folder $folder)
    {
        $this->folder = $folder;
    }
}
