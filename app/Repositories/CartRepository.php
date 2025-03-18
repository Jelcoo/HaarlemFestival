<?php

namespace App\Repositories;

use App\Config\Config;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\EventDance;
use App\Models\EventYummy;
use App\Models\EventHistory;
use App\Helpers\QueryBuilder;
use App\Enum\ItemQuantityEnum;
use App\Services\AssetService;
use App\Models\CartItemQuantity;

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

    public function getCartById(int $id, bool $includeItems = false, bool $includeEvents = false): ?Cart
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryCart = $queryBuilder->table('carts')->where('id', '=', $id)->first();

        $cart = $queryCart ? new Cart($queryCart) : null;

        if ($cart && $includeItems) {
            $cart->items = $this->getCartItemsByCartId($id, $includeEvents);
        }

        return $cart;
    }

    public function createCart(?int $user_id): Cart
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
            $cartItem->quantities = $this->getItemQuantities($cartItem->id);

            if ($includeEvents) {
                $cartItem->event = $this->getMixedEvent($cartItem->event_model, $cartItem->event_id);
            }

            return $cartItem;
        }, $queryItems);
    }

    private function getItemQuantities(int $cartItemId): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryQuantities = $queryBuilder->table('cart_item_quantities')->where('cart_item_id', '=', $cartItemId)->get();

        return array_map(function ($quantity) {
            return new CartItemQuantity($quantity);
        }, $queryQuantities);
    }

    private function getMixedEvent(string $eventModel, int $eventId): EventDance|EventHistory|EventYummy
    {
        $modelInstance = new $eventModel();

        if ($modelInstance instanceof EventDance) {
            $modelInstance = $this->danceRepository->getEventById($eventId);
            $modelInstance->location = $this->locationRepository->getLocationById($modelInstance->location_id);
            $modelInstance->location->assets = $this->assetService->resolveAssets($modelInstance->location, 'cover');
            $modelInstance->artists = $this->artistRepository->getArtistsByEventId($eventId);
        } elseif ($modelInstance instanceof EventHistory) {
            $modelInstance = $this->historyRepository->getEventById($eventId);
        } elseif ($modelInstance instanceof EventYummy) {
            $modelInstance = $this->yummyRepository->getEventById($eventId);
            $modelInstance->restaurant = $this->restaurantRepository->getRestaurantByIdWithLocation($modelInstance->restaurant_id);
            $modelInstance->restaurant->assets = $this->assetService->resolveAssets($modelInstance->restaurant, 'cover');
        }

        return $modelInstance;
    }

    public function increaseQuantity(int $cartId, int $cartItemId, ItemQuantityEnum $type): void
    {
        $maxQuantity = $this->getMaxQuantity($type);

        $sql = '
UPDATE cart_items ci
JOIN cart_item_quantities ciq ON ciq.cart_item_id = ci.id
SET ciq.quantity = ciq.quantity + 1
WHERE ci.id = :cartItemId
AND ci.cart_id = :cartId
AND ciq.type = :type';
    
        // Only enforce maxQuantity if it's not null
        if ($maxQuantity !== null) {
            $sql .= ' AND ciq.quantity < :maxQuantity';
        }

        $query = $this->getConnection()->prepare($sql);

        $params = [
            'cartItemId' => $cartItemId,
            'cartId' => $cartId,
            'type' => $type->value
        ];

        if ($maxQuantity !== null) {
            $params['maxQuantity'] = $maxQuantity;
        }

        $query->execute($params);
    }

    public function decreaseQuantity(int $cartId, int $cartItemId, ItemQuantityEnum $type): void
    {
        $minQuantity = $this->getMinQuantity($type);

        $query = $this->getConnection()->prepare('
UPDATE cart_items ci
JOIN cart_item_quantities ciq ON ciq.cart_item_id = ci.id
SET ciq.quantity = ciq.quantity - 1
WHERE ci.id = :cartItemId
AND ci.cart_id = :cartId
AND ciq.type = :type
AND ciq.quantity > :minQuantity');

        $query->execute([
            'cartItemId' => $cartItemId,
            'cartId' => $cartId,
            'type' => $type->value,
            'minQuantity' => $minQuantity
        ]);
    }

    private function getMaxQuantity(ItemQuantityEnum $type): int|null
    {
        return match ($type) {
            ItemQuantityEnum::GENERAL => Config::getKey('CART_DANCE_MAX'),
            ItemQuantityEnum::ADULT => Config::getKey('CART_YUMMY_ADULT_MAX'),
            ItemQuantityEnum::CHILD => Config::getKey('CART_YUMMY_CHILD_MAX'),
            ItemQuantityEnum::SINGLE => Config::getKey('CART_HISTORY_SINGLE_MAX'),
            ItemQuantityEnum::FAMILY => Config::getKey('CART_HISTORY_FAMILY_MAX'),
            default => null
        };
    }

    private function getMinQuantity(ItemQuantityEnum $type): int|null
    {
        return match ($type) {
            ItemQuantityEnum::GENERAL => Config::getKey('CART_DANCE_MIN'),
            ItemQuantityEnum::ADULT => Config::getKey('CART_YUMMY_ADULT_MIN'),
            ItemQuantityEnum::CHILD => Config::getKey('CART_YUMMY_CHILD_MIN'),
            ItemQuantityEnum::SINGLE => Config::getKey('CART_HISTORY_SINGLE_MIN'),
            ItemQuantityEnum::FAMILY => Config::getKey('CART_HISTORY_FAMILY_MIN'),
            default => 1
        };
    }

    public function addCartItem(CartItem $cartItem): void
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $cartItemId = $queryBuilder->table('cart_items')->insert([
            'cart_id' => $cartItem->cart_id,
            'event_id' => $cartItem->event_id,
            'event_model' => $cartItem->event_model,
            'note' => $cartItem->note,
        ]);

        foreach ($cartItem->quantities as $quantity) {
            $queryBuilder->table('cart_item_quantities')->insert([
                'cart_item_id' => $cartItemId,
                'type' => $quantity->type->value,
                'quantity' => $quantity->quantity,
            ]);
        }
    }

    public function deleteCartItem(int $cartId, int $cartItemId): void
    {
        $query = $this->getConnection()->prepare('
DELETE FROM cart_items
WHERE id = :cartItemId
AND cart_id = :cartId');

        $query->execute([
            'cartItemId' => $cartItemId,
            'cartId' => $cartId,
        ]);
    }
}
