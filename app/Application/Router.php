<?php

namespace App\Application;

use App\Models\Route;
use App\Controllers\ErrorController;

class Router
{
    private array $routes = [];
    private mixed $currentMiddleware = [];
    public Request $request;
    public Response $response;

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
    }

    private static Router $router;

    public static function getInstance(): Router
    {
        if (!isset(self::$router)) {
            self::$router = new Router();
        }

        return self::$router;
    }

    public function get(string $uri, array $callback): void
    {
        $this->routes[] = $this->constructRoute($uri, 'GET', $callback);
    }

    public function post(string $uri, array $callback): void
    {
        $this->routes[] = $this->constructRoute($uri, 'POST', $callback);
    }

    public function delete(string $uri, array $callback): void
    {
        $this->routes[] = $this->constructRoute($uri, 'DELETE', $callback);
    }

    public function middleware(mixed $middleware, callable $register): void
    {
        $this->currentMiddleware[] = new $middleware();
        $register();
        array_pop($this->currentMiddleware);
    }

    public function resolve(): void
    {
        $uri = $this->request->getPath();
        $method = $this->request->getMethod();
        $route = $this->resolveRoute($uri, $method);

        if (is_null($route) || !$route->executeMiddleware($route->params)) {
            $this->response->setStatusCode(404);
            $this->response->setContent((new ErrorController())->error404());
        } else {
            $route->callback[0] = new $route->callback[0]();

            $content = call_user_func_array($route->callback, [...$route->params]);
            $this->response->setContent($content);
        }

        $this->response->send();
    }

    private function resolveRoute(string $uri, string $method): ?Route
    {
        foreach ($this->routes as $route) {
            if ($route->method === $method) {
                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^\/]+)', $route->uri);
                $pattern = '#^' . $pattern . '$#';

                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches);
                    $route->params = $matches;

                    return $route;
                }
            }
        }

        return null;
    }

    private function constructRoute(string $uri, string $method, array $callback): Route
    {
        return new Route($uri, $method, $callback, $this->currentMiddleware);
    }
}
