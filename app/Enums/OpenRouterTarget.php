<?php

namespace App\Enums;

enum OpenRouterTarget: string
{
    case AUTO = 'auto';
    case GPT = 'gpt-4o';
    case MISTRAL = 'mistralai/mistral-large';
}
