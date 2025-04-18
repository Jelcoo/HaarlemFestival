<?php

use App\Middleware\EnsureAdmin;
use App\Middleware\EnsureEmployee;
use App\Middleware\EnsureLoggedIn;
use App\Middleware\EnsureNotLoggedIn;

$router = App\Application\Router::getInstance();

$router->get('/', [App\Controllers\HomeController::class, 'index']);

$router->get('/dance', [App\Controllers\EventControllers\DanceController::class, 'showMain']);
$router->get('/dance/{slug}_{id}', [App\Controllers\EventControllers\DanceController::class, 'showDetail']);

$router->get('/yummy', [App\Controllers\EventControllers\YummyController::class, 'showMain']);
$router->get('/yummy/{slug}_{id}', [App\Controllers\EventControllers\YummyController::class, 'showDetail']);

$router->get('/history', [App\Controllers\EventControllers\HistoryController::class, 'showMain']);
$router->get('/history/{slug}_{id}', [App\Controllers\EventControllers\HistoryController::class, 'showDetail']);

$router->get('/magic', [App\Controllers\EventControllers\MagicController::class, 'show']);

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

    $router->get('/forgot-password', [App\Controllers\AuthController::class, 'forgotPassword']);
    $router->post('/forgot-password', [App\Controllers\AuthController::class, 'forgotPasswordPost']);
    $router->get('/reset-password', [App\Controllers\AuthController::class, 'resetPassword']);
    $router->post('/reset-password', [App\Controllers\AuthController::class, 'resetPasswordPost']);
});

$router->middleware(EnsureLoggedIn::class, function () use ($router) {
    $router->get('/logout', [App\Controllers\AuthController::class, 'logout']);

    $router->get('/account/manage', [App\Controllers\ProfileController::class, 'index']);
    $router->post('/account/manage', [App\Controllers\ProfileController::class, 'update']);
    $router->post('/account/manage/password', [App\Controllers\ProfileController::class, 'updatePassword']);

    $router->post('/cart', [App\Controllers\CartController::class, 'checkout']);
    $router->get('/checkout', [App\Controllers\CheckoutController::class, 'index']);
    $router->post('/checkout/pay', [App\Controllers\CheckoutController::class, 'checkout']);
    $router->get('/checkout/complete', [App\Controllers\CheckoutController::class, 'completePayment']);
    $router->get('/checkout/pay_later', [App\Controllers\CheckoutController::class, 'payLater']);
    $router->middleware(EnsureEmployee::class, function () use ($router) {
        $router->get('/qrcode', [App\Controllers\QrController::class, 'index']);
    });

    $router->middleware(EnsureAdmin::class, function () use ($router) {
        $router->get('/dashboard', [App\Controllers\Dashboard\DashboardController::class, 'index']);

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
        $router->get('/dashboard/locations/create', [App\Controllers\Dashboard\LocationsController::class, 'createLocation']);
        $router->post('/dashboard/locations/create', [App\Controllers\Dashboard\LocationsController::class, 'createLocationPost']);
        $router->get('/dashboard/locations/edit', [App\Controllers\Dashboard\LocationsController::class, 'editLocation']);
        $router->post('/dashboard/locations/edit', [App\Controllers\Dashboard\LocationsController::class, 'editLocationPost']);
        $router->post('/dashboard/locations/delete', [App\Controllers\Dashboard\LocationsController::class, 'deleteLocation']);
        $router->get('/dashboard/locations/export', [App\Controllers\Dashboard\LocationsController::class, 'exportLocations']);

        $router->get('/dashboard/artists', [App\Controllers\Dashboard\ArtistsController::class, 'index']);
        $router->get('/dashboard/artists/create', [App\Controllers\Dashboard\ArtistsController::class, 'createArtist']);
        $router->post('/dashboard/artists/create', [App\Controllers\Dashboard\ArtistsController::class, 'createArtistPost']);
        $router->get('/dashboard/artists/edit', [App\Controllers\Dashboard\ArtistsController::class, 'editArtist']);
        $router->post('/dashboard/artists/edit', [App\Controllers\Dashboard\ArtistsController::class, 'editArtistPost']);
        $router->post('/dashboard/artists/delete', [App\Controllers\Dashboard\ArtistsController::class, 'deleteArtist']);
        $router->get('/dashboard/artists/export', [App\Controllers\Dashboard\ArtistsController::class, 'exportArtists']);

        $router->get('/dashboard/events/dance', [App\Controllers\Dashboard\DanceEventController::class, 'index']);
        $router->get('/dashboard/events/dance/create', [App\Controllers\Dashboard\DanceEventController::class, 'createDanceEvent']);
        $router->post('/dashboard/events/dance/create', [App\Controllers\Dashboard\DanceEventController::class, 'createDanceEventPost']);
        $router->get('/dashboard/events/dance/edit', [App\Controllers\Dashboard\DanceEventController::class, 'editDanceEvent']);
        $router->post('/dashboard/events/dance/edit', [App\Controllers\Dashboard\DanceEventController::class, 'editDanceEventPost']);
        $router->post('/dashboard/events/dance/delete', [App\Controllers\Dashboard\DanceEventController::class, 'deleteDanceEvent']);
        $router->get('/dashboard/events/dance/export', [App\Controllers\Dashboard\DanceEventController::class, 'exportDanceEvents']);

        $router->get('/dashboard/events/yummy', [App\Controllers\Dashboard\YummyEventController::class, 'index']);
        $router->get('/dashboard/events/yummy/create', [App\Controllers\Dashboard\YummyEventController::class, 'createYummyEvent']);
        $router->post('/dashboard/events/yummy/create', [App\Controllers\Dashboard\YummyEventController::class, 'createYummyEventPost']);
        $router->get('/dashboard/events/yummy/edit', [App\Controllers\Dashboard\YummyEventController::class, 'editYummyEvent']);
        $router->post('/dashboard/events/yummy/edit', [App\Controllers\Dashboard\YummyEventController::class, 'editYummyEventPost']);
        $router->post('/dashboard/events/yummy/delete', [App\Controllers\Dashboard\YummyEventController::class, 'deleteYummyEvent']);
        $router->get('/dashboard/events/yummy/export', [App\Controllers\Dashboard\YummyEventController::class, 'exportYummyEvents']);

        $router->get('/dashboard/events/history', [App\Controllers\Dashboard\HistoryEventController::class, 'index']);
        $router->get('/dashboard/events/history/create', [App\Controllers\Dashboard\HistoryEventController::class, 'createHistoryEvent']);
        $router->post('/dashboard/events/history/create', [App\Controllers\Dashboard\HistoryEventController::class, 'createHistoryEventPost']);
        $router->get('/dashboard/events/history/edit', [App\Controllers\Dashboard\HistoryEventController::class, 'editHistoryEvent']);
        $router->post('/dashboard/events/history/edit', [App\Controllers\Dashboard\HistoryEventController::class, 'editHistoryEventPost']);
        $router->post('/dashboard/events/history/delete', [App\Controllers\Dashboard\HistoryEventController::class, 'deleteHistoryEvent']);
        $router->get('/dashboard/events/history/export', [App\Controllers\Dashboard\HistoryEventController::class, 'exportHistoryEvents']);
    });
});
