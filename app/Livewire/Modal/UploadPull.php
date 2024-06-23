<?php

namespace App\Livewire\Modal;

use Api\Entities\Media\Image;
use App\Models\Attachment;
use Livewire\WithFileUploads;

class UploadPull extends Modal
{
    use WithFileUploads;

    public array $media = [];
    public array $uploadField = [];

    public string $uploadUrlField = '';

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

    public function uploadFromUrl()
    {
        if (blank($this->uploadUrlField)) {
            return;
        }

        Image::make()->source($this->uploadUrlField)->save();

        $this->toast('File uploaded successfully');
        $this->dispatch('refresh-media');

        $this->dispatch('close-modal');
    }
}
