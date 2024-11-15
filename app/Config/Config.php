<?php

namespace App\Config;

class Config
{
    public static function getKey(string $key): string
    {
        return $_ENV[$key] ?? '';
    }
}
