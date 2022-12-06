<?php

use App\Http\Controllers\ApiController;
use App\Http\Livewire;
use Illuminate\Support\Facades\Route;

Route::get('/', Livewire\Home::class)->name('home.index');

Route::get('/gallery/{filters?}', Livewire\Gallery::class)
    ->where('filters', '.*')
    ->name('gallery.index');

Route::get('/feed', Livewire\Feed::class)->name('feed.index');

Route::get('/tag-manager', Livewire\Home::class)->name('tag-manager.index');

Route::post('/api/v1/upload', [ApiController::class, 'store'])
    ->name('api.store');
