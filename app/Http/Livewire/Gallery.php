<?php

namespace App\Http\Livewire;

use App\Enums\Display;
use App\Enums\Sorting;
use App\Http\Livewire\Wireables\FilterBag;
use Livewire\Component;

class Gallery extends Component
{
    public FilterBag $bag;

    public int $randomizer;

    public Sorting $sort = Sorting::POPULAR;
    public Display $display = Display::CARD;

    public int $pagination = 12;

    public $loaded = false;

    public function mount()
    {
        if ($this->loaded) {
            $this->ready();
        }
    }

    public function ready()
    {
        $this->resetRandomizer();
        $this->bag = new FilterBag;

        $this->loaded = true;
    }

    public function render()
    {
        if (! $this->loaded) {
            return view('livewire.pre-load');
        }

        // Set the randomizer seed
        srand($this->randomizer);

        return view('livewire.gallery', [
            'pulls' => $this->bag->pulls()
                ->when($this->sort, fn ($items) => $this->sort->sortCollection($items))
                ->take($this->pagination),
        ]);
    }

    public function updatingSort(&$value)
    {
        $value = Sorting::from($value);

        if ($value->isRandomizer()) {
            $this->resetRandomizer();
        }
    }

    public function updatingDisplay(&$value)
    {
        $value = Display::from($value);
    }

    public function resetRandomizer()
    {
        $this->randomizer = now()->timestamp;
    }
}
