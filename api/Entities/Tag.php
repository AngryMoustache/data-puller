<?php

namespace Api\Entities;

use Api\Clients\OpenAI;
use App\Models\Tag as ModelsTag;
use Illuminate\Support\Str;

class Tag
{
    public bool $needsTranslation = false;

    public function __construct(public string $tag)
    {
        $this->needsTranslation(check_japanese($tag));
    }

    public function needsTranslation(bool $needsTranslation): self
    {
        $this->needsTranslation = $needsTranslation;

        return $this;
    }

    public function fetch(): null | ModelsTag
    {
        $name = $this->needsTranslation
            ? OpenAI::translateToEnglish($this->tag)
            : $this->tag;

        // Remove dots at the end of the strings (Pixiv)
        return ModelsTag::where('name', 'LIKE', rtrim($name, '.'))->first()
             ?? ModelsTag::where('slug', 'LIKE', Str::slug(rtrim($name, '.')))->first();
    }
}
