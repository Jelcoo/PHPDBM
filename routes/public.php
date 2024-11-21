<?php

use App\Middleware\EnsureLoggedIn;
use App\Middleware\EnsureNotLoggedIn;

$router = App\Application\Router::getInstance();

$router->middleware(EnsureNotLoggedIn::class, function () use ($router) {
    $router->get('/login', [App\Controllers\LoginController::class, 'index']);
    $router->post('/login', [App\Controllers\LoginController::class, 'login']);
});

$router->middleware(EnsureLoggedIn::class, function () use ($router) {
    $router->get('/', [App\Controllers\HomeController::class, 'index']);

    $router->get('/logout', [App\Controllers\LoginController::class, 'logout']);
});
