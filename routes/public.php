<?php

use App\Middleware\EnsureLoggedIn;

$router = App\Application\Router::getInstance();

$router->middleware(EnsureLoggedIn::class, function () use ($router) {
    $router->get('/', [App\Controllers\HomeController::class, 'index']);
});
