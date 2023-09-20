<?php

namespace App\Livewire\Pull;

use App\Enums\Status;
use App\Livewire\Traits\CanToast;
use App\Models\Folder;
use App\Models\History;
use App\Models\Pull;
use Illuminate\Support\Collection;
use Livewire\Component;

class Show extends Component
{
    use CanToast;

    public Pull $pull;

    public Collection $folders;

    public $listeners = [
        'refresh' => '$refresh',
    ];

    public function mount(Pull $pull)
    {
        app('site')->title($pull->name);

        if ($pull->status !== Status::ONLINE) {
            abort(404);
        }

        $this->pull = $pull;
        $this->folders = Folder::orderBy('name')
            ->whereHas('pulls')
            ->get();

        History::add($pull);
    }
}
