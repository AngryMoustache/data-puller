<?php

namespace App\Livewire\Feed;

use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class MediaList extends Component
{
    public array | Collection $media;

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
}
