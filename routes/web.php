<?php

use Api\Clients\DeviantArt;
use Api\Clients\Twitter;
use App\Http\Livewire\Feed;
use App\Http\Livewire\Gallery;
use Illuminate\Support\Facades\Route;

Route::get('/', Gallery::class)->name('gallery.index');
Route::get('/feed', Feed::class)->name('feed.index');

Route::get('/test', function () {
    (new Twitter)->likes()->each(fn ($pull) => $pull->save());
    (new DeviantArt)->favorites()->each(fn ($pull) => $pull->save());
});
