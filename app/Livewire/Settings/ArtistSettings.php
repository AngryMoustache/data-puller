<?php

namespace App\Livewire\Settings;

use App\Livewire\Traits\CanToast;
use App\Models\Artist;
use Livewire\Component;

class ArtistSettings extends Component
{
    use CanToast;

    public string $query = '';

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function render()
    {
        return view('livewire.settings.artist-settings', [
            'artists' => Artist::withoutGlobalScopes()
                ->where('parent_id', null)
                ->withCount('pulls')
                ->when($this->query, fn ($query) => $query->where('name', 'like', "%{$this->query}%"))
                ->orderBy('name')
                ->get(),
        ]);
    }
}
