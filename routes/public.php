<?php

$router = App\Application\Router::getInstance();

$router->get('/', [App\Controllers\HomeController::class, 'index']);
