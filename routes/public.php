<?php

use App\Middleware\EnsureLoggedIn;

$router = App\Application\Router::getInstance();

$router->get('/', [App\Controllers\HomeController::class, 'index']);

$router->get('/register', [App\Controllers\AuthController::class, 'register']);
$router->post('/register', [App\Controllers\AuthController::class, 'registerPost']);

$router->middleware(EnsureLoggedIn::class, function () use ($router) {
    $router->get('/account', [App\Controllers\HomeController::class, 'account']);
});
