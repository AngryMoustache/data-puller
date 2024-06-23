<?php

namespace App\Livewire\Prompt;

use Api\Entities\Media\Image;
use App\Enums\Origin;
use App\Enums\Status;
use App\Models\Artist;
use App\Models\Origin as ModelsOrigin;
use App\Models\Prompt;
use App\Models\Pull;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use WithFileUploads;

    public Prompt $prompt;

    public $sketch;

    public function mount(Prompt $prompt)
    {
        $this->prompt = $prompt;
    }

    public function updatedSketch()
    {
        $origin = ModelsOrigin::whereType(Origin::PROMPT)->first();

        $pull = new Pull([
            'origin_id' => $origin->id,
            'name' => $this->prompt->name ?? 'Daily prompt',
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
