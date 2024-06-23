<?php

use Api\Clients\OpenAI;
use App\Enums\Origin as EnumsOrigin;
use App\Enums\Status;
use App\Livewire;
use App\Models\Artist;
use App\Models\Attachment;
use App\Models\Origin;
use App\Models\Pull;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', Livewire\Home::class)->name('home.index');

Route::get('/pulls/{filterString?}', Livewire\Pull\Index::class)
    ->where('filterString', '.*')
    ->name('pull.index');

Route::get('/pull/random', fn () => redirect()->route('pull.show', [
    'pull' => Pull::online()->inRandomOrder()->first(),
]))->name('pull.random');

Route::get('/pull/{pull}', Livewire\Pull\Show::class)->name('pull.show');
Route::get('/pull/{pull}/translate', Livewire\Pull\Translate::class)->name('pull.translate');

Route::get('/prompts', Livewire\Prompt\Index::class)->name('prompt.index');
Route::get('/prompts/{prompt}', Livewire\Prompt\Show::class)->name('prompt.show');

Route::get('/folders', Livewire\Folder\Index::class)->name('folder.index');

Route::get('/slideshow/{slideshow}', Livewire\Slideshow\Show::class)->name('slideshow.show');

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

Route::get('/archive', Livewire\Archive\Index::class)->name('archive.index');

Route::get('/history', Livewire\History\Index::class)->name('history.index');

Route::get('/tag-groups', Livewire\TagGroup\Index::class)->name('tag-group.index');

Route::get('/settings', Livewire\Settings\Index::class)->name('settings.index');

Route::get('test', function () {
    // Artist::where('slug', '')->get()->each(function (Artist $artist) {
    //     $artist->update(['slug' => Str::slug(translate_japanese($artist->name))]);
    // });

    // Origin::where('type', EnumsOrigin::SCRAPER)->get()->map->pulls->flatten(1)->each(function (Pull $pull) {
    //     if (! Str::startsWith($pull->source_url, 'https://api.rule34.xxx/')) {
    //         return;
    //     }

    //     $pull->source_url = Str::replaceFirst(
    //         config('clients.scraper.detail_url'),
    //         config('clients.scraper.source_url'),
    //         $pull->source_url,
    //     );

    //     $pull->saveQuietly();
    // });
});
