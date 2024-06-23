<?php

namespace App\Livewire\Pull;

use App\Enums\Status;
use App\Livewire\Traits\CanToast;
use App\Models\Attachment;
use App\Models\JapaneseTranslation;
use App\Models\Pull;
use Illuminate\Support\Collection;
use Livewire\Component;

class Translate extends Component
{
    use CanToast;

    public Pull $pull;
    public Collection $media;
    public Attachment $current;
    public Collection $translations;

    protected $listeners = ['refresh' => '$refresh'];

    public function mount(Pull $pull)
    {
        app('site')->title("Translating {$pull->name}");

        $this->pull = $pull;
        $this->media = $pull->attachments;
        $this->setCurrent($this->media->first()->id);
    }

    public function newTranslation(array $location)
    {
        $this->translations->push(JapaneseTranslation::create([
            'pull_id' => $this->pull->id,
            'media_id' => $this->current->id,
            'translation' => '',
            'location' => $location,
        ]));
    }

    public function setCurrent(int $id)
    {
        $this->current = $this->media->firstWhere('id', $id);

        $this->translations = JapaneseTranslation::where([
            'pull_id' => $this->pull->id,
            'media_id' => $this->current->id,
        ])->get();

    }
}
