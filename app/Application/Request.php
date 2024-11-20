<?php

namespace App\Application;

class Request
{
    public static function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position === false) {
            return $path;
        }

        return substr($path, 0, $position);
    }

    public static function getMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public static function getPostField(string $name): string
    {
        return $_POST[$name] ?? '';
    }

    public static function getSession(): array
    {
        return $_SESSION ?? [];
    }
}
