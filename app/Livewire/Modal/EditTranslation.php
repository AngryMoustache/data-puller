<?php

namespace App\Livewire\Modal;

use App\Livewire\Traits\CanToast;
use App\Models\JapaneseTranslation;
use App\Models\Kanji;

class EditTranslation extends Modal
{
    use CanToast;

    public JapaneseTranslation $translation;

    public null | string $translationText = null;
    public null | string $originalText = null;

    public array $kanji = [];

    public function mount(array $params = [])
    {
        $this->translation = JapaneseTranslation::find($params['translationId']);

        $this->translationText = $this->translation->translation;
        $this->originalText = $this->translation->original;

        $this->kanji = $this->translation->kanji->map->toJsonObject()->toArray();
    }

    public function save()
    {
        $this->saveKanji();

        $this->translation->update([
            'translation' => $this->translationText,
            'original' => $this->originalText,
        ]);

        $this->toast('Translation updated successfully!');
        $this->dispatch('close-modal');
    }

    public function delete()
    {
        $this->translation->delete();
        $this->toast('Translation updated deleted!');
        $this->dispatch('refresh');
        $this->dispatch('close-modal');
    }

    public function addKanji(string $character)
    {
        $this->saveKanji();

        $kanji = Kanji::firstOrCreate(
            ['character' => $character],
            ['meaning' => Kanji::where('character', $character)->first()->meaning ?? ''],
        );

        if (blank($kanji->meaning)) {
            $kanji->update(['meaning' => $kanji->fetchMeaning()]);
        }

        if ($this->translation->kanji->contains($kanji)) {
            $this->toast('Kanji already exists in translation!');
            return;
        }

        $kanji->translations()->sync($this->translation->id);
        $this->translation->refresh();
        $this->kanji = $this->translation->kanji->map->toJsonObject()->toArray();
    }

    public function removeKanji(int $index)
    {
        $this->saveKanji();

        $this->translation->kanji()->detach($this->translation->kanji[$index]->id);
        $this->translation->refresh();
        $this->kanji = $this->translation->kanji->map->toJsonObject()->toArray();
    }

    public function saveKanji(): void
    {
        $this->translation->kanji->each(function ($kanji) {
            $kanji->update(['meaning' => collect($this->kanji)->first(fn ($k) => $k['id'] === $kanji->id)['meaning']]);
        });
    }
}
