<?php

use App\Middleware\TableExists;
use App\Middleware\DatabaseExists;
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

    $router->middleware(DatabaseExists::class, function () use ($router) {
        $router->get('/database/{database}', [App\Controllers\DatabaseController::class, 'show']);

        $router->get('/database/{database}/new', [App\Controllers\DatabaseController::class, 'newTable']);
        $router->post('/database/{database}/new', [App\Controllers\DatabaseController::class, 'createTable']);

        $router->middleware(TableExists::class, function () use ($router) {
            $router->get('/database/{database}/{table}', [App\Controllers\TableController::class, 'show']);
            $router->get('/database/{database}/{table}/edit', [App\Controllers\DatabaseController::class, 'editTable']);

            $router->get('/database/{database}/{table}/new', [App\Controllers\TableController::class, 'newRow']);
            $router->post('/database/{database}/{table}/new', [App\Controllers\TableController::class, 'createRow']);

            $router->get('/database/{database}/{table}/edit/{key}', [App\Controllers\TableController::class, 'editRow']);
            $router->post('/database/{database}/{table}/edit/{key}', [App\Controllers\TableController::class, 'updateRow']);
        });
    });
});
