<?php

namespace App\Livewire\Modal;

use AngryMoustache\Media\Formats\Thumb;
use App\Models\Attachment;
use App\Models\Tag;
use App\Models\TagGroup;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

class Formatter extends Modal
{
    public int $thumbnailKey;
    public array $thumbnail;

    public array $options = [];
    public array $initial = [];
    public array $formats = [];

    public Collection $tagGroups;

    public Attachment $attachment;

    public function mount(array $params = [])
    {
        $this->thumbnailKey = $params['thumbnailKey'] ?? false;
        $this->thumbnail = $params['thumbnail'] ?? [];

        $this->thumbnail['format'] ??= 'thumb-' . Str::random(8);

        $this->attachment = Attachment::find($this->thumbnail['attachment_id']);
        $this->options = Thumb::cropperOptions();

        $this->initial = ($this->attachment->crops ?? [])[$this->thumbnail['format']]
            ?? ($this->attachment->crops ?? [])['thumb']
            ?? [];

        $this->tagGroups = collect($params['tagList'] ?? [])
            ->map(function (array $group) {
                $group['tags'] = Tag::where('is_hidden', false)
                    ->whereIn('id', collect($group['tags'] ?? [])->filter()->keys())
                    ->orderBy('long_name')
                    ->get()
                    ->map(fn (Tag $tag) => [
                        'id' => $tag->id,
                        'name' => $tag->long_name,
                    ])
                    ->toArray();

                return $group;
            });

        // Fill the tags array in thumbnail so that Alpine knows what to do
        $this->thumbnail['tags'] = $this->tagGroups->mapWithKeys(fn (array $group) => [
            $group['id'] => $this->thumbnail['tags'][$group['id']] ?? []
        ])->toArray();
    }

    #[On('save-crop')]
    public function saveCrop(array $event)
    {
        $crop = $event['crop'];
        $data = $event['data'];
        $formatName = $this->thumbnail['format'];

        // Save the crop in the storage
        $url = $this->attachment->id . '/' . $formatName . '-' . $this->attachment->original_name;
        $crop = preg_replace('/data:image\/(.*?);base64,/' ,'', $crop);
        $crop = str_replace(' ', '+', $crop);

        Storage::disk('nas-media')->put("mobileart/public/attachments/{$url}", base64_decode($crop));

        // Save the crop on the attachment, for later adjustments
        $crops = $this->attachment->crops ?? [];
        $crops[$formatName] = $data;
        $this->attachment->crops = $crops;
        $this->attachment->save();

        $this->thumbnail['thumbnail_url'] = $this->attachment->getUrl($url);

        $this->toast('Cropped successfully!');

        $this->dispatch('close-modal');

        // Pass the thumbnail URL to the parent component
        $this->dispatch('updated-thumbnail-list', [
            'thumbnailKey' => $this->thumbnailKey,
            'thumbnail' => $this->thumbnail,
        ]);
    }
}
