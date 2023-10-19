<?php

namespace App\Livewire\Modal;

use App\Models\Attachment;
use App\Models\Pivot\MediaPull;
use App\Models\Video;
use App\PullMedia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class AddAttachment extends Modal
{
    use WithPagination;

    public array $selected;
    public bool $multiple;
    public string $target;

    public bool $forceLoading = false;

    public string $query = '';
    public string $mediaClass = Attachment::class;

    public function mount(array $params = [])
    {
        $this->selected = $params['selected'] ?? [];
        $this->multiple = $params['multiple'] ?? true;
        $this->target = $params['target'] ?? 'set-media';
    }

    public function render()
    {
        $media = DB::table('attachments')
            ->select([
                DB::raw('CONCAT("attachment-", `id`) as `concat_id`'),
                'original_name',
                'extension',
                'created_at',
            ])
            ->union(
                DB::table('videos')
                    ->select([
                        DB::raw('CONCAT("video-", `id`) as `concat_id`'),
                        'original_name',
                        'extension',
                        'created_at',
                    ])
                    ->when(filled($this->query), function ($query) {
                        $query->where('original_name', 'LIKE', "%{$this->query}%")
                            ->orWhere('extension', 'LIKE', "%{$this->query}%");
                    })
            )
            ->orderByDesc('created_at')
            ->when(filled($this->query), function ($query) {
                $query->where('original_name', 'LIKE', "%{$this->query}%")
                    ->orWhere('extension', 'LIKE', "%{$this->query}%");
            })
            ->paginate(15);

        return view('livewire.modal.add-attachment', [
            'attachments' => optional($media)->map(function (object $media) {
                if (Str::startsWith($media->concat_id, 'video-')) {
                    $media = Video::find(Str::after($media->concat_id, 'video-'));
                } else {
                    $media = Attachment::find(Str::after($media->concat_id, 'attachment-'));
                }

                return new PullMedia($media);
            }),
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

    #[On('refresh-media')]
    public function refreshMedia()
    {
        $this->resetPage();
        $this->query = '';
    }
}
