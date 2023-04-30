<?php

use App\Http\Livewire;
use Illuminate\Support\Facades\Route;

Route::get('/', Livewire\Home::class)->name('home.index');

Route::get('/pulls/{filterString?}', Livewire\Pull\Index::class)
    ->where('filterString', '.*')
    ->name('pull.index');

Route::get('/pull/{pull}', Livewire\Pull\Show::class)->name('pull.show');

Route::get('/folders', Livewire\Folder\Index::class)->name('folder.index');

Route::get('/feed', Livewire\Feed\Index::class)->name('feed.index');
Route::get('/feed/{pull:id}', Livewire\Feed\Show::class)->name('feed.show');

Route::get('/history', Livewire\History\Index::class)->name('history.index');
