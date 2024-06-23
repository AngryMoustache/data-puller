<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Kanji extends Model
{
    protected $table = 'kanji';

    protected $fillable = [
        'character',
        'meaning',
    ];

    public function translations()
    {
        return $this->belongsToMany(JapaneseTranslation::class);
    }

    public function route(): string
    {
        return "https://jisho.org/search/{$this->character}%20%23kanji";
    }

    public function toJsonObject(): array
    {
        return [
            'id' => $this->id,
            'character' => $this->character,
            'meaning' => $this->meaning,
            'route' => $this->route(),
        ];
    }

    public function fetchMeaning(): string
    {
        $json = Http::get("https://kanjiapi.dev/v1/kanji/{$this->character}")->json();

        return collect($json['meanings'])->join(', ');
    }
}
