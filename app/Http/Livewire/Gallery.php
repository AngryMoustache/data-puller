<?php

namespace App\Http\Livewire;

use App\Models\Origin;
use App\Models\Pull;
use Illuminate\Support\Collection;
use Livewire\Component;

class Gallery extends Component
{
    public Collection $origins;
    public array $enabledOrigins;

    public function mount()
    {
        $this->origins = Origin::orderBy('name', 'desc')->get();
        $this->enabledOrigins = $this->origins->mapWithKeys(fn ($i) => [$i->id => true])->toArray();
    }

    public function render()
    {
        $origins = collect($this->enabledOrigins)->filter()->keys();
        $pulls = Pull::orderBy('id', 'desc')->whereIn('origin_id', $origins)->get();

        return view('livewire.gallery', [
            'pulls' => $pulls->skip(1),
            'latest' => $pulls->first(),
            'enabled' => $origins,
        ]);
    }
}
