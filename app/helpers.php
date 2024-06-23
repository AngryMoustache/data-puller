<?php

use Api\Clients\OpenAI;
use Illuminate\Support\Str;

if (! function_exists('check_japanese')) {
    function check_japanese(null | string $value): bool
    {
        return mb_detect_encoding($value) !== 'ASCII';
    }
}

if (! function_exists('translate_japanese')) {
    function translate_japanese($value, $fallback = null)
    {
        if (check_japanese($value)) {
            $value = OpenAI::translateToEnglish($value);
        }

        if (empty($value)) {
            $value = $fallback;
        }

        return Str::of($value)->trim();
    }
}
