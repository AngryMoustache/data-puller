<?php

use App\Http\Livewire;
use Illuminate\Support\Facades\Route;

Route::get('/', Livewire\Home::class)->name('home.index');

Route::get('/pulls', Livewire\Home::class)->name('pull.index');
Route::get('/pulls/{pull}', Livewire\PullDetail::class)->name('pull.show');

Route::get('/feed', Livewire\Home::class)->name('feed.index');
