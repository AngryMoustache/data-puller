<?php

use App\Http\Livewire;
use Illuminate\Support\Facades\Route;

Route::get('/', Livewire\Home::class)->name('home.index');

Route::get('/pulls/{filterString?}', Livewire\Pull\Index::class)
    ->where('filterString', '.*')
    ->name('pull.index');

Route::get('/pull/{pull}', Livewire\Pull\Show::class)->name('pull.show');

Route::get('/feed', Livewire\Home::class)->name('feed.index');

Route::get('/history', Livewire\History\Index::class)->name('history.index');
