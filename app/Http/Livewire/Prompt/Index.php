<?php

namespace App\Http\Livewire\Prompt;

use Api\Entities\Media\Image;
use App\Enums\Origin;
use App\Enums\Status;
use App\Models\Artist;
use App\Models\Origin as ModelsOrigin;
use App\Models\Prompt;
use App\Models\Pull;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;

    public Prompt $prompt;

    public Collection $previous;

    public $sketch;

    public function mount()
    {
        $this->prompt = Prompt::getDay();
        $this->previous = Prompt::orderBy('date', 'desc')
            ->whereHas('pull', fn ($q) => $q->online())
            ->get();
    }

    public function updatedSketch()
    {
        $origin = ModelsOrigin::whereType(Origin::PROMPT)->first();

        $pull = new Pull([
            'origin_id' => $origin->id,
            'name' => Pull::getAIName($this->prompt->tags) ?? 'Daily prompt',
            'artist_id' => Artist::guess($origin->api_target)->id,
            'status' => Status::PENDING,
        ]);

        $pull->save();

        $tags = $this->prompt->tags->map(function ($tag) {
            $tags = [$tag];

            while ($tag->parent) {
                $tag = $tag->parent;
                $tags[] = $tag;
            }

            return $tags;
        })->flatten();

        $pull->tags()->sync($tags->pluck('id')->unique());

        $attachment = Image::make()
            ->source($this->sketch->getRealPath())
            ->save();

        $pull->attachments()->sync($attachment->id);

        $pull = $pull->refresh();

        $this->prompt->update(['pull_id' => $pull->id]);

        return redirect()->route('feed.show', $pull->id);
    }
}
