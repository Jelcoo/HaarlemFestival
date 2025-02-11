<?php

use App\Middleware\EnsureLoggedIn;

$router = App\Application\Router::getInstance();

$router->get('/', [App\Controllers\HomeController::class, 'index']);

$router->middleware(EnsureLoggedIn::class, function () use ($router) {
    $router->get('/account', [App\Controllers\HomeController::class, 'account']);
});
