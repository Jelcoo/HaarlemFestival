<?php

use App\Middleware\EnsureLoggedIn;

$router = App\Application\Router::getInstance();

$router->get('/', [App\Controllers\HomeController::class, 'index']);

$router->get('/dashboard', [App\Controllers\DashboardController::class, 'index']);
$router->get('/dashboard/users', [App\Controllers\DashboardUsersController::class, 'index']);
$router->post('/dashboard/users', [App\Controllers\DashboardUsersController::class, 'handleAction']);

$router->middleware(EnsureLoggedIn::class, function () use ($router) {
    $router->get('/account', [App\Controllers\HomeController::class, 'account']);
});
