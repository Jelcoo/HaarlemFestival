<?php

namespace App\Repositories;

use App\Helpers\QueryBuilder;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Event;
use App\Models\EventDance;
use App\Models\EventHistory;
use App\Models\EventYummy;
use App\Services\AssetService;

class CartRepository extends Repository
{
    private DanceRepository $danceRepository;
    private HistoryRepository $historyRepository;
    private YummyRepository $yummyRepository;
    private LocationRepository $locationRepository;
    private RestaurantRepository $restaurantRepository;
    private ArtistRepository $artistRepository;
    private AssetService $assetService;

    public function __construct()
    {
        parent::__construct();
        $this->danceRepository = new DanceRepository();
        $this->historyRepository = new HistoryRepository();
        $this->yummyRepository = new YummyRepository();
        $this->locationRepository = new LocationRepository();
        $this->restaurantRepository = new RestaurantRepository();
        $this->artistRepository = new ArtistRepository();
        $this->assetService = new AssetService();
    }

    public function getCartById(int $id, bool $includeItems = false): Cart
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryCart = $queryBuilder->table('carts')->where('id', '=', $id)->first();

        $cart = $queryCart ? new Cart($queryCart) : null;

        if ($cart && $includeItems) {
            $cart->items = $this->getCartItemsByCartId($id);
        }

        return $cart;
    }

    public function createCart(int|null $user_id): Cart
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $userId = $queryBuilder->table('carts')->insert([
            'user_id' => $user_id,
        ]);
        $cart = $this->getCartById((int) $userId);

        return $cart;
    }

    public function getCartItemsByCartId(int $cartId): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryItems = $queryBuilder->table('cart_items')->where('cart_id', '=', $cartId)->get();

        return array_map(function ($item) {
            $cartItem = new CartItem($item);
            $cartItem->event = $this->getMixedEvent($cartItem->event_model, $cartItem->event_id);

            return $cartItem;
        }, $queryItems);
    }

    private function getMixedEvent(string $eventModel, int $eventId): Event|null
    {
        $modelInstance = new $eventModel();

        if ($modelInstance instanceof EventDance) {
            $modelInstance = $this->danceRepository->getEventById($eventId);
            $modelInstance->location = $this->locationRepository->getLocationById($modelInstance->location_id);
            $modelInstance->location->assets = $this->assetService->resolveAssets($modelInstance->location, 'cover');
            $modelInstance->artists = $this->artistRepository->getArtistsByEventId($eventId);
        } else if ($modelInstance instanceof EventHistory) {
            $modelInstance = $this->historyRepository->getEventById($eventId);
        } else if ($modelInstance instanceof EventYummy) {
            $modelInstance = $this->yummyRepository->getEventById($eventId);
            $modelInstance->restaurant = $this->restaurantRepository->getRestaurantByIdWithLocation($modelInstance->restaurant_id);
            $modelInstance->restaurant->assets = $this->assetService->resolveAssets($modelInstance->restaurant, 'cover');
        }

        return $modelInstance;
    }
}
