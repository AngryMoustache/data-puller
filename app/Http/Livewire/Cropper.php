<?php

namespace App\Http\Livewire;

use AngryMoustache\Media\Formats\Thumb;
use AngryMoustache\Rambo\Http\Livewire\Cropper as LivewireCropper;

class Cropper extends LivewireCropper
{
    public $current = Thumb::class;

    public array $options = [];
    public array $initial = [];

    public function mount()
    {
        parent::mount();

        $this->options = Thumb::cropperOptions();
        $this->initial = ($this->attachment->crops ?? [])['thumb'] ?? [];
    }

    public function render()
    {
        return view('livewire.cropper');
    }
}
