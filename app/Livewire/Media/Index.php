<?php

namespace App\Livewire\Media;

use Api\Entities\ApiUpload;
use Api\Entities\Media\Image;
use App\Enums\Origin as EnumsOrigin;
use App\Enums\Status;
use App\Livewire\Traits\CanToast;
use App\Livewire\Traits\HasPreLoading;
use App\Models\Attachment;
use App\Models\Origin;
use App\Models\Pull;
use App\Models\Video;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    use HasPreLoading;
    use CanToast;

    #[Url]
    public int $page = 1;

    #[Url]
    public int $perPage = 240;

    public function render()
    {
        app('site')->title('Media Explorer');

        if (! $this->loaded) {
            return $this->renderLoadingGridContainer(24);
        }

        return view('livewire.media.index', [
            'items' => $this->fetchFiles(),
        ]);
    }

    public function delete(string $path)
    {
        $id = Str::between($path, 'attachments/', '/');
        $files = collect(Storage::disk('nas-media')->allFiles("mobileart/public/attachments/{$id}"));

        $check = $files
            ->reject(fn (string $_p) => Str::contains($_p, Str::afterLast($path, '/')))
            ->isEmpty();

        if (! $check) {
            $this->toast('There are other images inside this folder, please check the NAS');

            return;
        }

        $files->each(function (string $path) {
            Storage::disk('nas-media')->move($path, "trash/{$path}");
        });

        Storage::disk('nas-media')->deleteDirectory("mobileart/public/attachments/{$id}");
        Cache::forget("folder-contents-{$id}");

        $this->toast('The image has been moved to trash');
    }

    public function pull(string $path)
    {
        $attachment = Attachment::find(
            Str::between($path, 'attachments/', '/')
        );

        // Check if the attachment we selected is not a rogue image
        if ($attachment->original_name !== Str::afterLast($path, '/')) {
            $size = getimagesize($path);

            $attachment = Image::make()
                ->source($path)
                ->name(Str::afterLast($path, '/'))
                ->size($size[0], $size[1])
                ->save();
        }

        $origin = Origin::where('type', EnumsOrigin::EXTERNAL)->firstOrFail();

        $pull = Pull::create([
            'origin_id' => $origin->id,
            'name' => 'Media explorer pull',
            'status' => Status::PENDING,
        ]);

        $pull->attachments()->attach($attachment);

        $this->toast('The pull has been created and will be processed shortly');
    }

    private function fetchFiles()
    {
        $usedMedia = Attachment::whereIn('id', collect([
            DB::table('media_pull')
                ->where('media_type', 'LIKE', "%Attachment")
                ->pluck('media_id'),
            Video::pluck('preview_id'),
        ])->flatten()->filter()->unique())->pluck('original_name');

        $folder = 1;
        $counter = 0;
        $items = collect();
        $itemsToGetRange = range(
            $this->perPage * ($this->page - 1),
            ($this->perPage * $this->page) - 1
        );

        $max = Attachment::latest()->first()->id + 1;

        while ($items->count() < count($itemsToGetRange) && $folder < $max) {
            info($folder);
            $this->getFolderContents($folder)->each(function (string $path) use (&$items, &$counter, $usedMedia) {
                if ($usedMedia->contains(Str::afterLast($path, '/'))) {
                    return;
                }

                if ($counter++ < $this->perPage * ($this->page - 1)) {
                    return;
                }

                $items->push((object) [
                    'path' => $path,
                    'url' => (new Attachment)->getUrl(Str::afterLast($path, 'attachments/')),
                ]);
            });

            $folder++;
        }

        return $items;
    }

    private function getFolderContents(int $folder)
    {
        return Cache::rememberForever("folder-contents-{$folder}", function () use ($folder) {
            return collect(Storage::disk('nas-media')->allFiles("mobileart/public/attachments/{$folder}"))
                ->reject(fn (string $path) => Str::contains($path, [
                    '/thumb-',
                    '/resized-',
                ]));
        });
    }
}
