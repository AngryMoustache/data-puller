<?php

namespace App\Http\Livewire\Prompt;

use App\Http\Livewire\Traits\HasPagination;
use App\Http\Livewire\Traits\HasPreLoading;
use App\Models\Prompt;
use Livewire\Component;

class Index extends Component
{
    use HasPreLoading;
    use HasPagination;

    public int $perPage = 6;

    public function render()
    {
        app('site')->title('Prompts');

        if (! $this->loaded) {
            return $this->renderLoadingListContainer(3);
        }

        $query = Prompt::orderBy('date', 'desc');

        return view('livewire.prompt.index', [
            'prompts' => $query->limit($this->page * $this->perPage)->get(),
            'hasMore' => $query->count() > ($this->page * $this->perPage),
        ]);
    }
}
