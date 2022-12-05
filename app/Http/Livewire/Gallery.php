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
    public int $pagination = 24;

    public bool $loaded = false;

    public Sorting $sort = Sorting::POPULAR;
    public Display $display = Display::COMPACT;

    protected $listeners = [
        'infinite-scroll-trigger' => 'addPage',
    ];

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

        srand($this->randomizer); // Set the randomizer seed

        $pulls = $this->bag->pulls()
            ->when($this->sort, fn ($items) => $this->sort->sortCollection($items));

        return view('livewire.gallery', [
            'pulls' => $pulls->take($this->pagination),
            'maxPulls' => $pulls->count(),
        ]);
    }

    public function addPage()
    {
        $this->pagination += 24;
    }

    public function setSort($value)
    {
        $this->sort = Sorting::from($value);

        if ($this->sort->isRandomizer()) {
            $this->resetRandomizer();
        }
    }

    public function setDisplay($value)
    {
        $this->display = Display::from($value);
    }

    public function resetRandomizer()
    {
        $this->randomizer = now()->timestamp;
    }
}
