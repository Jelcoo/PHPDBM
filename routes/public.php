<?php

use App\Middleware\EnsureNotLoggedIn;

$router = App\Application\Router::getInstance();

$router->get('/', [App\Controllers\HomeController::class, 'index']);

$router->middleware(EnsureNotLoggedIn::class, function () use ($router) {
    $router->get('/login', [App\Controllers\LoginController::class, 'index']);
    $router->post('/login', [App\Controllers\LoginController::class, 'login']);
});
