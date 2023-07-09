<?php

namespace App\Http\Livewire;

use AngryMoustache\Media\Formats\Thumb;
use AngryMoustache\Media\Models\Attachment;
use AngryMoustache\Rambo\Http\Livewire\Cropper as LivewireCropper;
use App\Models\Video;

class Cropper extends LivewireCropper
{
    public $current = Thumb::class;

    public array $options = [];
    public array $initial = [];

    public $listeners = [
        'cropped' => 'saveCrop',
        'update-cropper-attachment' => 'updateAttachment',
    ];

    public function mount()
    {
        parent::mount();

        $this->options = Thumb::cropperOptions();
        $this->initial = ($this->attachment->crops ?? [])['thumb'] ?? [];
    }

    public function saveCrop($event)
    {
        parent::saveCrop($event);

        $this->initial = ($this->attachment->crops ?? [])['thumb'] ?? [];
        $this->dispatchBrowserEvent('init-cropper');
    }

    public function render()
    {
        return view('livewire.cropper');
    }

    public function updateAttachment(string $id)
    {
        [$class, $id] = explode(':', $id);

        if ($class === Video::class) {
            $this->attachment = Video::find($id)->preview;
        } else {
            $this->attachment = Attachment::find($id);
        }

        $this->initial = ($this->attachment->crops ?? [])['thumb'] ?? [];
        $this->dispatchBrowserEvent('init-cropper');
    }
}
