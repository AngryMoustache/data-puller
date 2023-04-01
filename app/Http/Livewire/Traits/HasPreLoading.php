<?php

namespace App\Http\Livewire\Traits;

trait HasPreLoading
{
    public bool $loaded = false;

    public function ready()
    {
        $this->loaded = true;
    }

    public function renderLoading()
    {
        return view('livewire.loading.spinner');
    }

    public function renderLoadingGrid(int $size = 5)
    {
        return view('livewire.loading.grid', [
            'size' => $size,
        ]);
    }

    public function renderLoadingGridContainer(int $size = 5)
    {
        return view('livewire.loading.grid-container', [
            'size' => $size,
        ]);
    }

    public function renderLoadingList(int $size = 5)
    {
        return view('livewire.loading.list', [
            'size' => $size,
        ]);
    }
}
