<?php

use App\Middleware\EnsureLoggedIn;

$router = App\Application\Router::getInstance();

$router->get('/', [App\Controllers\HomeController::class, 'index']);
$router->get('/cart', [App\Controllers\StripeController::class, 'cart']);
$router->get('/checkout', [App\Controllers\StripeController::class, 'index']);
$router->post('/checkout/create', [App\Controllers\StripeController::class, 'create']);

$router->middleware(EnsureLoggedIn::class, function () use ($router) {
    $router->get('/account', [App\Controllers\HomeController::class, 'account']);
});
