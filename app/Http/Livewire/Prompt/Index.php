<?php

namespace App\Http\Livewire\Prompt;

use Api\Entities\Media\Image;
use App\Enums\Origin;
use App\Enums\Status;
use App\Models\Origin as ModelsOrigin;
use App\Models\Prompt;
use App\Models\Pull;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;

    public Prompt $prompt;

    public $sketch;

    public function mount()
    {
        $this->prompt = Prompt::day();
    }

    public function uploadSketch()
    {
        $pull = new Pull([
            'origin_id' => ModelsOrigin::whereType(Origin::PROMPT)->first()?->id,
            'name' => Pull::getAIName($this->prompt->tags) ?? 'Daily prompt',
            'status' => Status::PENDING,
            'source_url' => rand(0, 100000000),
        ]);

        $pull->save();

        $pull->tags()->sync($this->prompt->tags->pluck('id'));

        $attachment = Image::make()
            ->source($this->sketch->getRealPath())
            ->save();

        $pull->attachments()->sync($attachment->id);

        $this->prompt->update([
            'pull_id' => $pull->id,
        ]);

        return redirect()->route('feed.show', $pull->id);
    }
}
