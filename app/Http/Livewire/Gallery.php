<?php

namespace App\Http\Livewire;

use App\Enums\Display;
use App\Enums\Sorting;
use App\Models\Origin;
use App\Models\Pull;
use Livewire\Component;

class Gallery extends Component
{
    public int $randomizer = 0;
    public int $pagination = 48;

    public bool $loaded = false;

    public string $query = '';
    public array $selectedTags = [];
    public ?Origin $origin = null;
    public Sorting $sort = Sorting::NEWEST;
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
        $this->randomizer = $filters->get('randomizer', 0);
        $this->origin = Origin::whereSlug($filters->get('origin'))->first();
        $this->sort = Sorting::tryFrom($filters['sort'] ?? '') ?? Sorting::default();
        $this->display = Display::tryFrom($filters['display'] ?? '') ?? Display::default();
        $this->selectedTags = explode(',', $filters->get('tags', ''));
    }

    public function ready()
    {
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
            ->with('origin', 'tags')
            ->when($this->query !== '', function ($query) {
                return $query->where(function ($subQuery) {
                    $subQuery->where('name', 'LIKE', "%{$this->query}%")
                        ->orWhereHas('tags', fn ($q) => $q->where('name', 'LIKE', "%{$this->query}%"));
                });
            })
            ->when($this->selectedTags !== [], function ($query) {
                return $query->whereHas('tags', fn ($q) => $q->whereIn('slug', $this->selectedTags));
            })
            ->when($this->origin, function ($query) {
                return $query->whereHas('origin', fn ($q) => $q->where('slug', $this->origin->slug));
            })
            ->get()
            ->when($this->sort, fn ($items) => $this->sort->sortCollection($items));

        return view('livewire.gallery', [
            'pulls' => $pulls->take($this->pagination),
            'maxPulls' => $pulls->count(),
            'origins' => Origin::pluck('name', 'slug')->sort(),
        ]);
    }

    public function addPage()
    {
        $this->pagination += 48;
    }

    public function setFilterValues($sort, $display, $origin)
    {
        $this->sort = Sorting::from($sort);

        if ($this->sort->isRandomizer()) {
            $this->resetRandomizer();
        } else {
            $this->randomizer = 0;
        }

        $this->display = Display::from($display);

        $this->origin = Origin::whereSlug($origin)->first();
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
            'randomizer' => $this->randomizer,
            'display' => $this->display->value,
            'origin' => $this->origin?->slug,
            'tags' => implode(',', $this->selectedTags),
        ])
            ->filter()
            ->reject(fn ($value) => in_array($value, [
                // Default values
                Sorting::default()->value,
                Display::default()->value,
            ]))
            ->map(fn ($value, $key) => "{$key}:{$value}")
            ->implode('/');
    }
}
