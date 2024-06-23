<?php

namespace Api\Clients;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI as FacadesOpenAI;

class OpenAI
{
    public static function getNameBasedOnTags(iterable $tags): string
    {
        $tags = Collection::wrap($tags)->pluck('long_name')->join(', ');

        return static::response(config('openai.name_generation_prompt') . $tags . '.');
    }

    public static function getPromptBasedOnTags(iterable $tags): string
    {
        $tags = Collection::wrap($tags)->pluck('long_name')->join(', ');

        return static::response(config('openai.drawing_prompt') . $tags . '.', [
            'max_tokens' => 300,
        ]);
    }

    public static function reply(string $prompt, array $options = []): string
    {
        return static::response($prompt, $options);
    }

    public static function translateToEnglish(string $text): string
    {
        return Cache::rememberForever(md5("openai-translate-{$text}"), function () use ($text) {
            return static::response("Convert the following Japanese characters to English and ONLY return the result: {$text}.");
        });
    }

    private static function response(string $prompt, array $options = []): string
    {
        $result = FacadesOpenAI::chat()->create(array_merge([
            'model' => 'gpt-4o',
            'messages' => [['role' => 'user', 'content' => $prompt]],
            'max_tokens' => 150,
            'temperature' => 0.7,
            'top_p' => 1,
        ], $options));

        return Str::of($result['choices'][0]['message']['content'] ?? '')
            ->replace('"', '')
            ->trim()
            ->__toString();
    }
}
