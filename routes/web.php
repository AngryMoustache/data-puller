<?php

use App\Http\Controllers\ApiController;
use App\Http\Livewire;
use Illuminate\Support\Facades\Route;

Route::get('/', Livewire\Home::class)->name('home.index');
Route::get('/gallery', Livewire\Gallery::class)->name('gallery.index');
Route::get('/feed', Livewire\Home::class)->name('feed.index');
Route::get('/tag-manager', Livewire\Home::class)->name('tag-manager.index');

Route::get('/test', function () {
    \App\Models\Origin::get()->each(fn ($origin) => $origin->pull());
});

Route::post('/api/v1/upload', [ApiController::class, 'store'])
    ->name('api.store');
