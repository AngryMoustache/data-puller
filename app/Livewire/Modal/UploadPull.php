<?php

namespace App\Livewire\Modal;

use AngryMoustache\Media\Models\Attachment;
use Livewire\WithFileUploads;

class UploadPull extends Modal
{
    use WithFileUploads;

    public array $media = [];
    public array $uploadField = [];

    public function save()
    {
        foreach ($this->media as $file) {
            Attachment::livewireUpload($file);
        }

        $this->toast('Files uploaded successfully');
        $this->dispatch('refresh-media');

        $this->dispatch('close-modal');
    }

    public function updatedUploadField(array $value)
    {
        foreach ($value as $file) {
            $this->media[] = $file;
        }
    }

    public function removeFile(int $key)
    {
        unset($this->media[$key]);
    }
}
