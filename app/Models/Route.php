<?php

namespace App\Models;

use App\Middleware\Middleware;

class Route
{
    public string $uri;
    public string $method;
    public array $callback;
    public array $middleware;
    public array $params = [];

    public function __construct(string $uri, string $method, array $callback, ?Middleware $middleware = null)
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->callback = $callback;
        $this->middleware = $middleware ? [$middleware] : [];
    }

    public function executeMiddleware(array $params = []): bool
    {
        foreach ($this->middleware as $middleware) {
            if (!$middleware->verify($params)) {
                return false;
            }
        }

        return true;
    }
}
