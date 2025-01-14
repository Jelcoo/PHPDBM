<?php

namespace App\Application;

class Response
{
    private int $statusCode = 200;
    private mixed $content;

    public function setStatusCode(int $code): void
    {
        $this->statusCode = $code;
    }

    public function setContent(mixed $content): void
    {
        $this->content = $content;
    }

    public function isJson(): bool
    {
        return is_array($this->content) || is_object($this->content);
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        echo $this->content;
    }

    public function sendJson(): void
    {
        header('Content-Type: application/json');
        http_response_code($this->statusCode);
        echo json_encode($this->content);
    }

    public static function redirect(string $url): void
    {
        header('Location: ' . $url);
        http_response_code(302);
        exit;
    }
}
