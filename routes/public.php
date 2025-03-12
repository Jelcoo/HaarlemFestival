<?php

use App\Middleware\EnsureAdmin;
use App\Middleware\EnsureEmployee;
use App\Middleware\EnsureLoggedIn;
use App\Middleware\EnsureNotLoggedIn;

$router = App\Application\Router::getInstance();

$router->get('/', [App\Controllers\HomeController::class, 'index']);
$router->get('/dance', [App\Controllers\HomeController::class, 'dance']);
$router->get('/yummy', [App\Controllers\HomeController::class, 'yummy']);
$router->get('/history', [App\Controllers\HomeController::class, 'history']);
$router->get('/magic', [App\Controllers\HomeController::class, 'magic']);
$router->get('/cart', [App\Controllers\CartController::class, 'index']);
$router->post('/order/create', [App\Controllers\CartController::class, 'createOrder']);


$router->middleware(EnsureNotLoggedIn::class, function () use ($router) {
    $router->get('/register', [App\Controllers\AuthController::class, 'register']);
    $router->post('/register', [App\Controllers\AuthController::class, 'registerPost']);

    $router->get('/login', [App\Controllers\AuthController::class, 'login']);
    $router->post('/login', [App\Controllers\AuthController::class, 'loginPost']);
});

$router->middleware(EnsureLoggedIn::class, function () use ($router) {
    $router->get('/logout', [App\Controllers\AuthController::class, 'logout']);

    $router->get('/account/manage', [App\Controllers\ProfileController::class, 'index']);
    $router->post('/account/manage', [App\Controllers\ProfileController::class, 'update']);
    $router->post('/account/manage/password', [App\Controllers\ProfileController::class, 'updatePassword']);

    $router->post('/cart', [App\Controllers\CartController::class, 'checkout']);
    $router->get('/checkout', [App\Controllers\CheckoutController::class, 'index']);
    $router->get('/checkout/pay_later', [App\Controllers\CheckoutController::class, 'payLater']);
    $router->middleware(EnsureEmployee::class, function () use ($router) {
        $router->get('/qrcode', [App\Controllers\QrController::class, 'index']);
    });

    $router->middleware(EnsureAdmin::class, function () use ($router) {
        $router->get('/dashboard', [App\Controllers\DashboardController::class, 'index']);
        $router->get('/dashboard/users', [App\Controllers\DashboardUsersController::class, 'index']);
        $router->post('/dashboard/users', [App\Controllers\DashboardUsersController::class, 'handleAction']);
    });
});
