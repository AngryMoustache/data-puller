<?php

namespace App\Livewire\Modal;

use Api\Jobs\RebuildCache;
use App\Livewire\Traits\CanToast;
use App\Models\Artist;
use App\Models\Pull;
use Illuminate\Support\Collection;

class EditArtist extends Modal
{
    use CanToast;

    public Artist $artist;
    public Collection $artistList;

    public string $name;
    public string $childSearch = '';
    public array $children = [];

    public function mount(array $params = [])
    {
        $this->artist = Artist::find($params['id']);
        $this->name = $this->artist->name;

        $this->children = $this->artist->children
            ->mapWithKeys(fn (Artist $artist) => [$artist->id => ['value' => $artist->name, 'key' => $artist->id]])
            ->toArray();

        $this->artistList = Artist::where('id', '!=', $this->artist->id)->get()
            ->map(fn (Artist $artist) => ['value' => $artist->name, 'key' => $artist->id]);
    }

    public function selectChild(array $child)
    {
        $this->children[$child['key']] = $child;
    }

    public function removeChild(int $key)
    {
        Artist::whereIn('id', [$key])->update(['parent_id' => null]);

        unset($this->children[$key]);
        $this->dispatch('refresh');
    }

    public function save()
    {
        $this->artist->update(['name' => $this->name]);

        Artist::whereIn('id', array_keys($this->children))->update([
            'parent_id' => $this->artist->id,
        ]);

        Pull::whereIn('artist_id', array_keys($this->children))->update([
            'artist_id' => $this->artist->id,
        ]);

        RebuildCache::dispatch();

        $this->toast('Artist(s) updated successfully');
        $this->dispatch('close-modal');
        $this->dispatch('refresh');
    }
}
