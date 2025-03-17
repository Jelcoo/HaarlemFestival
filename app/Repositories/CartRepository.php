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

    public function getCartById(int $id, bool $includeItems = false, bool $includeEvents = false): Cart
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryCart = $queryBuilder->table('carts')->where('id', '=', $id)->first();

        $cart = $queryCart ? new Cart($queryCart) : null;

        if ($cart && $includeItems) {
            $cart->items = $this->getCartItemsByCartId($id, $includeEvents);
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

    public function getCartItemsByCartId(int $cartId, bool $includeEvents): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryItems = $queryBuilder->table('cart_items')->where('cart_id', '=', $cartId)->get();

        return array_map(function ($item) use ($includeEvents) {
            $cartItem = new CartItem($item);

            if ($includeEvents) {
                $cartItem->event = $this->getMixedEvent($cartItem->event_model, $cartItem->event_id);
            }

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

    public function increaseQuantity(int $cartId, int $cartItemId): void
    {
        $query = $this->getConnection()->prepare("
UPDATE cart_items
SET quantity = quantity + 1
WHERE id = :cartItemId
AND cart_id = :cartId");

        $query->execute([
            'cartItemId' => $cartItemId,
            'cartId' => $cartId
        ]);
    }

    public function decreaseQuantity(int $cartId, int $cartItemId): void
    {
        $query = $this->getConnection()->prepare("
UPDATE cart_items
SET quantity = quantity - 1
WHERE id = :cartItemId
AND cart_id = :cartId
AND quantity > 1");

        $query->execute([
            'cartItemId' => $cartItemId,
            'cartId' => $cartId
        ]);
    }

    public function addCartItem(int $cartId, int $eventId, string $eventModel, int $quantity): void
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryBuilder->table('cart_items')->insert([
            'cart_id' => $cartId,
            'event_id' => $eventId,
            'event_model' => $eventModel,
            'quantity' => $quantity
        ]);
    }

    public function deleteCartItem(int $cartId, int $cartItemId): void
    {
        $query = $this->getConnection()->prepare("
DELETE FROM cart_items
WHERE id = :cartItemId
AND cart_id = :cartId");

        $query->execute([
            'cartItemId' => $cartItemId,
            'cartId' => $cartId
        ]);
    }
}
