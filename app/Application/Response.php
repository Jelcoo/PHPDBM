<?php

namespace App\Application;

class Response
{
    private int $statusCode = 200;
    private string $content;

    public function setStatusCode(int $code): void
    {
        $this->statusCode = $code;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        echo $this->content;
    }

    public static function redirect(string $url): void
    {
        header('Location: ' . $url);
    }
}
