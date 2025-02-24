<?php

use App\Middleware\EnsureLoggedIn;
use App\Middleware\EnsureNotLoggedIn;

$router = App\Application\Router::getInstance();

$router->get('/', [App\Controllers\HomeController::class, 'index']);


$router->middleware(EnsureNotLoggedIn::class, function () use ($router) {
    $router->get('/register', [App\Controllers\AuthController::class, 'register']);
    $router->post('/register', [App\Controllers\AuthController::class, 'registerPost']);

    $router->get('/login', [App\Controllers\AuthController::class, 'login']);
    $router->post('/login', [App\Controllers\AuthController::class, 'loginPost']);
});

$router->middleware(EnsureLoggedIn::class, function () use ($router) {
    $router->get('/logout', [App\Controllers\AuthController::class, 'logout']);

    $router->get('/account', [App\Controllers\HomeController::class, 'account']);
});
