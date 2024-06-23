<?php

namespace App\Livewire\Pull;

use App\Enums\Status;
use App\Livewire\Traits\CanToast;
use App\Models\Folder;
use App\Models\History;
use App\Models\Pull;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

class Show extends Component
{
    use CanToast;

    public Pull $pull;

    public Collection $folders;

    public Collection $stories;

    public $listeners = [
        'refresh' => '$refresh',
    ];

    public function mount(Pull $pull)
    {
        app('site')->title($pull->name);

        if ($pull->status !== Status::ONLINE) {
            abort(404);
        }

        $this->pull = $pull;
        $this->folders = Folder::orderBy('name')
            ->whereHas('pulls')
            ->get();

        $this->stories = collect($this->pull->story);

        History::add($pull);
    }

    public function generateStory()
    {
        $story = $this->pull->generateStory();

        $this->pull->story = array_merge([[
            'title' => Str::of($story)->before("\n")->replace('*', ''),
            'body' => $story,
        ]], Arr::wrap($this->pull->story));

        $this->pull->saveQuietly();

        $this->stories = collect($this->pull->story);

        $this->dispatch('load-new-story');
    }

    public function getStory(int $key)
    {
        $story = $this->stories->get($key)['body'] ?? null;

        return $story
            ? (string) Markdown::parse(nl2br($story))
            : null;
    }
}
