<?php

namespace App\Livewire\Feed;

use Api\Entities\Media\Image;
use App\Livewire\Traits\CanToast;
use App\Models\Attachment;
use App\Models\Video;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class MediaList extends Component
{
    use CanToast;

    public array | Collection $media;

    public bool $showPreviewGenerator = false;

    public function mount(array $media)
    {
        $this->updateMedia($media);
    }

    #[On('update-media-list')]
    public function updateMedia(array $media)
    {
        $this->media = collect($media)->map(function (array $item) {
            [$class, $id] = explode(':', $item['id']);

            return $class::find($id);
        });
    }

    public function captureImage(string $base64, int $videoId)
    {
        $name = "{$videoId}-image-" . Str::uuid();
        $this->capture($name, $base64);

        $this->toast('Image created!');
    }

    public function capturePreview(string $base64, int $videoId)
    {
        $name = "{$videoId}-image-" . Str::uuid();

        $video = Video::find($videoId);
        $video->preview_id = $this->capture($name, $base64)->id;
        $video->save();

        $this->toast('Preview created and attached!');
        $this->dispatch('refresh-media-list');
    }

    private function capture(string $name, string $base64): Attachment
    {
        return Image::make()
            ->base64($base64)
            ->name($name)
            ->filename("{$name}.png")
            ->extension('png')
            ->save();
    }
}
