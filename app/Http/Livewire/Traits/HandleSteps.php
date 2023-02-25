<?php

namespace App\Http\Livewire\Traits;

use AngryMoustache\Rambo\Fields\AttachmentField;
use AngryMoustache\Rambo\Fields\ManyAttachmentField;
use AngryMoustache\Rambo\Http\Livewire\Wireables\WiredRamboItem;
use App\Models\Pull;

trait HandleSteps
{
    public int $currentStep = 4;

    public function mediaForm()
    {
        return [
            AttachmentField::make('preview_id'),
            ManyAttachmentField::make('attachments')->sortField('sort_order'),
        ];
    }

    public function gotoStep($step)
    {
        $this->saveStep();
        $this->currentStep = $step;
    }

    public function nextPull()
    {
        $this->pull = Pull::pending()->first();
        if (! $this->pull) {
            return;
        }

        $this->fields = [
            'name' => $this->pull->name,
            'artist' => $this->pull->artist,
            'preview_id' => $this->pull->preview_id,
            'attachments' => $this->pull->attachments->pluck('id'),
            'tags' => $this->pull->tags->pluck('id')->mapWithKeys(fn ($id) => [$id => true]),
        ];
    }

    private function refetch()
    {
        $this->pull = Pull::withoutGlobalScopes()->find($this->pull->id);
    }

    public function fieldUpdated($value, $field)
    {
        $field = (new WiredRamboItem)->fromLivewire($field);
        $this->fields[$field->getName()] = $field->getWithPivotData($value) ?? $value;
    }

    private function saveStep()
    {
        $tags = collect($this->fields['tags'])->filter()->keys()->toArray();

        $this->pull->name = $this->fields['name'];
        $this->pull->artist = $this->fields['artist'];
        $this->pull->attachments()->sync($this->fields['attachments']);
        $this->pull->tags()->sync($tags);

        $this->pull->saveQuietly();
        $this->refetch();
    }

}
