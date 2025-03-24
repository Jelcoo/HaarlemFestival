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
$router->post('/cart/increase', [App\Controllers\CartController::class, 'increaseQuantity']);
$router->post('/cart/decrease', [App\Controllers\CartController::class, 'decreaseQuantity']);
$router->post('/cart/add', [App\Controllers\CartController::class, 'addItem']);
$router->post('/cart/remove', [App\Controllers\CartController::class, 'removeItem']);

$router->post('/stripe/webhook', [App\Controllers\CheckoutController::class, 'webhook']);

$router->get('/program', [App\Controllers\ProgramController::class, 'index']);
$router->get('/program/tickets', [App\Controllers\ProgramController::class, 'tickets']);
$router->get('/program/tickets/qrcode', [App\Controllers\ProgramController::class, 'qrcode']);

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
    $router->get('/checkout/pay', [App\Controllers\CheckoutController::class, 'checkout']);
    $router->post('/checkout/create', [App\Controllers\CheckoutController::class, 'createCheckout']);
    $router->get('/checkout/complete', [App\Controllers\CheckoutController::class, 'completePayment']);
    $router->get('/checkout/pay_later', [App\Controllers\CheckoutController::class, 'payLater']);
    $router->middleware(EnsureEmployee::class, function () use ($router) {
        $router->get('/qrcode', [App\Controllers\QrController::class, 'index']);
    });

    $router->middleware(EnsureAdmin::class, function () use ($router) {
        $router->get('/dashboard', [App\Controllers\DashboardController::class, 'index']);

        $router->get('/dashboard/users', [App\Controllers\Dashboard\UsersController::class, 'index']);
        $router->get('/dashboard/users/create', [App\Controllers\Dashboard\UsersController::class, 'createUser']);
        $router->post('/dashboard/users/create', [App\Controllers\Dashboard\UsersController::class, 'createUserPost']);
        $router->get('/dashboard/users/edit', [App\Controllers\Dashboard\UsersController::class, 'editUser']);
        $router->post('/dashboard/users/edit', [App\Controllers\Dashboard\UsersController::class, 'editUserPost']);
        $router->post('/dashboard/users/delete', [App\Controllers\Dashboard\UsersController::class, 'deleteUser']);
        $router->get('/dashboard/users/export', [App\Controllers\Dashboard\UsersController::class, 'exportUsers']);

        $router->get('/dashboard/orders', [App\Controllers\Dashboard\OrderController::class, 'index']);
        $router->get('/dashboard/orders/tickets', [App\Controllers\Dashboard\OrderTicketsController::class, 'index']);

        $router->get('/dashboard/restaurants', [App\Controllers\Dashboard\RestaurantsController::class, 'index']);
        $router->get('/dashboard/restaurants/create', [App\Controllers\Dashboard\RestaurantsController::class, 'createRestaurant']);
        $router->post('/dashboard/restaurants/create', [App\Controllers\Dashboard\RestaurantsController::class, 'createRestaurantPost']);
        $router->get('/dashboard/restaurants/edit', [App\Controllers\Dashboard\RestaurantsController::class, 'editRestaurant']);
        $router->post('/dashboard/restaurants/edit', [App\Controllers\Dashboard\RestaurantsController::class, 'editRestaurantPost']);
        $router->post('/dashboard/restaurants/delete', [App\Controllers\Dashboard\RestaurantsController::class, 'deleteRestaurant']);
        $router->get('/dashboard/restaurants/export', [App\Controllers\Dashboard\RestaurantsController::class, 'exportRestaurants']);

        $router->get('/dashboard/locations', [App\Controllers\Dashboard\LocationsController::class, 'index']);
        $router->post('/dashboard/locations', [App\Controllers\Dashboard\LocationsController::class, 'handleAction']);

        $router->get('/dashboard/artists', [App\Controllers\Dashboard\ArtistsController::class, 'index']);
        $router->post('/dashboard/artists', [App\Controllers\Dashboard\ArtistsController::class, 'handleAction']);

    });
});
