<?php

namespace App\Http\Livewire;

use App\Enums\Display;
use App\Enums\Sorting;
use App\Models\Pull;
use Livewire\Component;

class Gallery extends Component
{
    public int $randomizer;
    public int $pagination = 24;

    public bool $loaded = false;

    public string $query = '';
    public Sorting $sort = Sorting::POPULAR;
    public Display $display = Display::COMPACT;

    protected $listeners = [
        'infinite-scroll-trigger' => 'addPage',
    ];

    public function mount($filters = '')
    {
        $filters = collect(explode('/', $filters))
            ->map(fn ($filter) => explode(':', $filter))
            ->filter(fn ($filter) => count($filter) > 1)
            ->mapWithKeys(fn ($filter) => [$filter[0] => $filter[1]]);

        $this->query = $filters->get('query', '');
        $this->sort = Sorting::tryFrom($filters['sort'] ?? '') ?? Sorting::POPULAR;
        $this->display = Display::tryFrom($filters['display'] ?? '') ?? Display::COMPACT;

        if ($this->loaded) {
            $this->ready();
        }
    }

    public function ready()
    {
        $this->resetRandomizer();
        $this->loaded = true;
    }

    public function render()
    {
        if (! $this->loaded) {
            return view('livewire.pre-load');
        }

        // Set the randomizer seed
        srand($this->randomizer);

        // Update the URL
        $this->dispatchBrowserEvent('update-url', [
            'url' => route('gallery.index', $this->buildQueryString()),
        ]);

        // Get the pulls
        $pulls = Pull::online()
            ->with('tags', 'origin')
            ->when($this->query !== '', function ($query) {
                return $query
                    ->where('name', 'LIKE', "%{$this->query}%")
                    ->orWhereHas('tags', fn ($q) => $q->where('name', 'LIKE', "%{$this->query}%"));
            })
            ->get()
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

    private function buildQueryString()
    {
        return collect([
            'query' => $this->query,
            'sort' => $this->sort->value,
            'display' => $this->display->value,
        ])
            ->filter()
            ->map(fn ($value, $key) => "{$key}:{$value}")
            ->implode('/');
    }
}
