<?php

namespace App\Livewire;

use AngryMoustache\Media\Formats\Thumb;
use AngryMoustache\Media\Models\Attachment;
use App\Livewire\Traits\CanToast;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class Cropper extends Component
{
    use CanToast;

    public $current = Thumb::class;

    public array $options = [];
    public array $initial = [];
    public array $formats = [];

    public Attachment $attachment;

    public string $hash;

    public function mount()
    {
        $this->options = Thumb::cropperOptions();
        $this->initial = ($this->attachment->crops ?? [])['thumb'] ?? [];
    }

    #[On('update-cropper-attachment')]
    public function updateAttachment(string $id)
    {
        [$class, $id] = explode(':', $id);

        if ($class === Video::class) {
            $this->attachment = Video::find($id)->preview;
        } else {
            $this->attachment = Attachment::find($id);
        }

        $this->initial = ($this->attachment->crops ?? [])['thumb'] ?? [];
        $this->dispatch('init-cropper');
    }

    #[On('cropped')]
    public function saveCrop(array $event)
    {
        $crop = $event['crop'];
        $data = $event['data'];

        $format = $this->formatName();
        $url = $this->attachment->id . '/' . ($format ? $format . '-' : '') . $this->attachment->original_name;

        // Save the crop in the storage
        $crop = preg_replace('/data:image\/(.*?);base64,/' ,'', $crop);
        $crop = str_replace(' ', '+', $crop);

        Storage::disk($this->attachment->disk)->put($url, base64_decode($crop));

        // Save the crop on the attachment, for later adjustments
        $crops = $this->attachment->crops ?? [];
        $crops[$format] = $data;
        $this->attachment->crops = $crops;
        $this->attachment->save();

        $this->toast("Cropped ${format} successfully!");

        // Reset the cropper
        $this->initial = ($this->attachment->crops ?? [])['thumb'] ?? [];
        $this->dispatch('init-cropper');
    }

    private function formatName()
    {
        return lcfirst(Str::afterLast($this->current, '\\'));
    }
}
