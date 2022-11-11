<?php

use App\Http\Livewire\Gallery;
use App\Models\Pull;
use Illuminate\Support\Facades\Route;

Route::get('/', Gallery::class)->name('gallery.index');

Route::get('/test', function () {
    \App\Models\Origin::get()->each(fn ($origin) => $origin->pull());
});
