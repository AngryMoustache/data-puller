<?php

use App\Enums\Origin as EnumsOrigin;
use App\Enums\Status;
use App\Livewire;
use App\Models\Origin;
use App\Models\Pull;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', Livewire\Home::class)->name('home.index');

Route::get('/pulls/{filterString?}', Livewire\Pull\Index::class)
    ->where('filterString', '.*')
    ->name('pull.index');

Route::get('/pull/random', fn () => redirect()->route('pull.show', [
    'pull' => Pull::online()->inRandomOrder()->first(),
]))->name('pull.random');

Route::get('/pull/{pull}', Livewire\Pull\Show::class)->name('pull.show');

Route::get('/prompts', Livewire\Prompt\Index::class)->name('prompt.index');
Route::get('/prompts/{prompt}', Livewire\Prompt\Show::class)->name('prompt.show');

Route::get('/folders', Livewire\Folder\Index::class)->name('folder.index');

Route::get('/feed/new', function () {
    $pull = Pull::updateOrCreate([
        'name' => 'New pull',
        'status' => Status::CREATING,
        'origin_id' => Origin::where('type', EnumsOrigin::EXTERNAL)->first()->id,
    ]);

    return redirect()->route('feed.show', ['pull' => $pull]);
})->name('feed.create');

Route::get('/feed', Livewire\Feed\Index::class)->name('feed.index');
Route::get('/feed/{pull:id}', Livewire\Feed\Show::class)->name('feed.show');

Route::get('/history', Livewire\History\Index::class)->name('history.index');

// Route::get('/things', function () {
//     \App\Models\Pull::with('tags')->get()->skip(1)->each(function ($pull) {
//         $group = \App\Models\TagGroup::updateOrCreate([
//             'pull_id' => $pull->id,
//             'name' => 'Main tags',
//             'is_main' => true,
//         ]);

//         $group->tags()->sync($pull->tags->pluck('id'));
//     });
// });

// Route::get('test', function () {
//     dd(Storage::disk('nas-media')->allFiles());
// });

Route::get('storage/{file}', function (string $file) {
    return redirect('https://media.mobileart.dev/public/' . $file);
})->where('file', '.*');
