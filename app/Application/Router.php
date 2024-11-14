<?php

namespace App\Application;

class Router {
    private array $routes = [];
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

    /**
     * @param string $uri
     * @param array $callback
     */
    public function get(string $uri, array $callback): void
    {
        $this->routes['GET'][$uri] = $callback;
    }

    /**
     * @param string $uri
     * @param array $callback
     */
    public function post(string $uri, array $callback): void
    {
        $this->routes['POST'][$uri] = $callback;
    }

    /**
     * @param string $uri
     * @param array $callback
     */
    public function delete(string $uri, array $callback): void
    {
        $this->routes['DELETE'][$uri] = $callback;
    }

    public function resolve()
    {
        $uri = $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$uri] ?? false;
        if ($callback === false) {
            $this->response->setStatusCode(404);
            $this->response->setContent('404 Not Found');
        } else {
            $callback[0] = new $callback[0]();
            $content = call_user_func($callback, $this->request, $this->response);
            $this->response->setContent($content);
        }

        $this->response->send();
    }
}
