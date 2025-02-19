<?php

use App\Middleware\EnsureLoggedIn;
use App\Middleware\EnsureNotLoggedIn;

$router = App\Application\Router::getInstance();

$router->get('/', [App\Controllers\HomeController::class, 'index']);

$router->get('/dashboard', [App\Controllers\DashboardController::class, 'index']);
$router->get('/dashboard/users', [App\Controllers\DashboardController::class, 'users']);

$router->middleware(EnsureLoggedIn::class, function () use ($router) {
    $router->get('/account', [App\Controllers\HomeController::class, 'account']);
});
