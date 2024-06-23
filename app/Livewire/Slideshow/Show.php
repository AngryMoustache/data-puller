<?php

namespace App\Livewire\Slideshow;

use App\Models\Pull;
use App\Models\Slideshow;
use Livewire\Component;

class Show extends Component
{
    public Slideshow $slideshow;

    public function render()
    {
        app('site')->title('Slideshow');

        $pulls = Pull::whereIn('id', $this->slideshow->ids)
            ->orderByRaw('FIELD(id, ' . collect($this->slideshow->ids)->join(',') . ')')
            ->get();

        return view('livewire.slideshow.show', [
            'images' => $pulls
                ->map->attachments
                ->flatten(1)
                ->map->path(),
        ])->layout('components.layouts.slideshow');
    }
}
