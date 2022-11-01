<?php

use App\Http\Livewire\Feed;
use App\Http\Livewire\Gallery;
use Illuminate\Support\Facades\Route;

Route::get('/', Gallery::class)->name('gallery.index');

Route::get('/filter/{filters}', Gallery::class)
    ->name('gallery.index')
    ->where('filters', '.*');

Route::get('/feed', Feed::class)->name('feed.index');

// Route::get('/test', function () {
//     Origin::get()->each(fn (Origin $origin) => $origin->pull());
// });
