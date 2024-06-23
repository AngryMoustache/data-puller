<?php

namespace App\Livewire\Settings;

use App\Livewire\Traits\CanToast;
use App\Models\Origin;
use Illuminate\Support\Collection;
use Livewire\Component;

class OriginSettings extends Component
{
    use CanToast;

    public Collection $origins;

    public function mount()
    {
        $this->origins = Origin::withoutGlobalScopes()->get()
            ->filter(fn (Origin $origin) => $origin->type->canPull())
            ->mapWithkeys(fn (Origin $origin) => [$origin->id => [
                'blade' => view('components.origin', ['origin' => $origin])->render(),
                'online' => $origin->online,
            ]]);
    }

    public function save()
    {
        $this->origins->each(function (array $origin, int $id) {
            Origin::withoutGlobalScopes()->find($id)->update([
                'online' => $origin['online'],
            ]);
        });

        $this->toast('Origins saved!');
    }
}
