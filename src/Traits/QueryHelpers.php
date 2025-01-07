<?php

namespace Kreatept\DBLayer\Traits;

trait QueryHelpers
{
    public function escapeLike(string $value): string
    {
        return str_replace(['%', '_'], ['\\%', '\\_'], $value);
    }

    public function sanitizeInput($input): string
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}
