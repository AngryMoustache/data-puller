<?php

namespace App\Http\Livewire\Feed;

use Illuminate\Support\Collection;
use Livewire\Component;

class MediaList extends Component
{
    public array | Collection $media;

    protected $listeners = [
        'update-media-list' => 'updateMedia',
    ];

    public function mount(array $media)
    {
        $this->updateMedia($media);
    }

    public function updateMedia(array $media)
    {
        $this->media = collect($media)->map(function (array $item) {
            [$class, $id] = explode(':', $item['id']);

            return $class::find($id);
        });
    }
}
