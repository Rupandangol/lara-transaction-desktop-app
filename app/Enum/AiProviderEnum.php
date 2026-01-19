<?php

namespace App\Enum;

enum AiProviderEnum: string
{
    case GEMINI = 'GEMINI';
    case CHATGPT = 'CHATGPT';
    case CLAUDE = 'CLAUDE';
}
