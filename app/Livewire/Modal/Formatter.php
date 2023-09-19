<?php

namespace App\Livewire\Modal;

use AngryMoustache\Media\Formats\Thumb;
use AngryMoustache\Media\Models\Attachment;
use App\Livewire\Traits\CanToast;
use App\Models\Tag;
use App\Models\Video;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class Formatter extends Component
{
    use CanToast;

    public array $options = [];
    public array $initial = [];
    public array $formats = [];

    public Collection $tagGroups;

    public Attachment $attachment;

    public bool $isMainThumbnail;
    public array $thumbnailFor = [];

    public function mount(array $params = [])
    {
        $this->isMainThumbnail = $params['isMainThumbnail'] ?? false;

        $this->tagGroups = collect($params['tagList'] ?? [])
            ->map(function (array $group) {
                $group['tags'] = Tag::where('is_hidden', false)
                    ->whereIn('id', collect($group['tags'] ?? [])->filter()->keys())
                    ->orderBy('long_name')
                    ->get()
                    ->map(fn (Tag $tag) => [
                        'id' => $tag->id,
                        'name' => $tag->long_name,
                        'thumbnail_url' => $tag->thumbnail_url,
                    ])
                    ->toArray();

                $group['slug'] = Str::camel($group['name']);
                $this->thumbnailFor[$group['slug']] = [];

                return $group;
            });

        [$class, $id] = explode(':', $params['attachment']);

        if ($class === Video::class) {
            $this->attachment = Video::find($id)->preview;
        } else {
            $this->attachment = Attachment::find($id);
        }

        $this->options = Thumb::cropperOptions();
        $this->initial = ($this->attachment->crops ?? [])['thumb'] ?? [];
    }

    #[On('save-crop')]
    public function saveCrop(array $event)
    {
        $crop = $event['crop'];
        $data = $event['data'];
        $thumbnails = [];

        $thumbnailsToGenerate = ['thumb'];
        if (! $this->isMainThumbnail){
            $thumbnailsToGenerate = collect(Arr::dot($this->thumbnailFor))
                ->map(fn ($value, $key) => Str::before($key, '.') . '-' . $value)
                ->values()
                ->toArray();
        }

        foreach ($thumbnailsToGenerate as $name) {
            $formatName = $this->getFormatName($name);

            // Save the crop in the storage
            $url = $this->attachment->id . '/' . $formatName . '-' . $this->attachment->original_name;
            $crop = preg_replace('/data:image\/(.*?);base64,/' ,'', $crop);
            $crop = str_replace(' ', '+', $crop);

            Storage::disk($this->attachment->disk)->put($url, base64_decode($crop));

            // Save the crop on the attachment, for later adjustments
            $crops = $this->attachment->crops ?? [];
            $crops[$formatName] = $data;
            $this->attachment->crops = $crops;
            $this->attachment->save();

            if (! $this->isMainThumbnail) {
                $thumbnails[] = [
                    'tag_id' => (int) Str::after($name, '-'),
                    'group' => $this->tagGroups->firstWhere('slug', Str::before($name, '-'))['name'] ?? '',
                    'thumbnail_url' => Storage::disk($this->attachment->disk)->url($url)
                ];
            }
        }

        $this->toast("Cropped successfully!");

        $this->dispatch('close-modal');

        // Pass the thumbnail URL to the parent component
        $this->dispatch('updated-thumbnails', [
            'thumbnails' => $thumbnails,
            'isMainThumbnail' => $this->isMainThumbnail,
        ]);
    }

    private function getFormatName(string $tag)
    {
        $formatName = 'thumb';
        if (! $this->isMainThumbnail) {
            $formatName .= "-{$tag}";
        }

        return $formatName;
    }
}
