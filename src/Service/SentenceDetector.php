<?php

namespace App\Service;

use Symfony\Component\String\UnicodeString;
use Text_LanguageDetect;

class SentenceDetector
{
    public function __construct()
    {

    }

    public function detectSentences(string $text): array
    {
        $sentences = preg_split('/[.!?]/', $text, -1, PREG_SPLIT_NO_EMPTY);

        return $sentences;
    }
}
