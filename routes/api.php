<?php

use App\Middleware\EnsureLoggedIn;

$router = App\Application\Router::getInstance();

$router->middleware(EnsureLoggedIn::class, function () use ($router) {
    $router->get('/api/databases', [App\Controllers\ApiController::class, 'databases']);
});
