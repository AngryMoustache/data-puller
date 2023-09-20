<?php

namespace App\Livewire\Modal;

use App\Models\Pivot\MediaPull;
use App\PullMedia;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AddAttachment extends Component
{
    use WithPagination;

    public array $selected;
    public bool $multiple;
    public string $target;

    public bool $forceLoading = false;

    public string $query = '';

    public function mount(array $params = [])
    {
        $this->selected = $params['selected'] ?? [];
        $this->multiple = $params['multiple'] ?? true;
        $this->target = $params['target'] ?? 'set-media';
    }

    public function render()
    {
        $media = MediaPull::orderByDesc('id')
            ->whereIn('id', DB::table('media_pull')
                ->select(DB::raw('MAX(`id`) as max_id'), 'media_type', 'media_id')
                ->groupBy('media_type')
                ->groupBy('media_id')
                ->pluck('max_id')
            )
            ->when(filled($this->query), function ($query) {
                $query->whereHas('media', function ($query) {
                    $query->where('original_name', 'LIKE', "%{$this->query}%")
                        ->orWhere('extension', 'LIKE', "%{$this->query}%");
                });

                $query->orWhereHas('pull', function ($query) {
                    $query->where('name', 'LIKE', "%{$this->query}%");
                });
            })
            ->whereHas('media')
            ->paginate(15)
            ->pluck('media')
            ->filter()
            ->mapInto(PullMedia::class);

        return view('livewire.modal.add-attachment', [
            'attachments' => $media,
        ]);
    }

    public function addSelected(array $selected)
    {
        // We close the modal in the target
        $this->dispatch($this->target, $selected);
        $this->forceLoading = true;
    }

    public function updatedQuery()
    {
        $this->resetPage();
    }
}
