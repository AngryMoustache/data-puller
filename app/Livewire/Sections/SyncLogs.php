<?php

namespace App\Livewire\Sections;

use App\Livewire\Traits\CanToast;
use App\Livewire\Traits\HasPagination;
use App\Livewire\Traits\HasPreLoading;
use App\Models\SyncLog;
use Livewire\Component;

#[\Livewire\Attributes\Isolate]
class SyncLogs extends Component
{
    use HasPreLoading;
    use HasPagination;
    use CanToast;

    public int $perPage = 1;

    public function render()
    {
        if (! $this->loaded) {
            return $this->renderLoadingList($this->perPage);
        }

        return view('livewire.sections.sync-logs', [
            'logs' => SyncLog::get(),
        ]);
    }

    public function handle(int $id)
    {
        SyncLog::find($id)?->update(['handled' => true]);
        $this->toast('Sync log has been handled.');
    }
}
