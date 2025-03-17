<?php

namespace App\Repositories;

use App\Helpers\QueryBuilder;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Event;
use App\Models\EventDance;
use App\Models\EventHistory;
use App\Models\EventYummy;

class CartRepository extends Repository
{
    private DanceRepository $danceRepository;
    private HistoryRepository $historyRepository;
    private YummyRepository $yummyRepository;

    public function __construct()
    {
        parent::__construct();
        $this->danceRepository = new DanceRepository();
        $this->historyRepository = new HistoryRepository();
        $this->yummyRepository = new YummyRepository();
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
            return $this->danceRepository->getEventById($eventId);
        } else if ($modelInstance instanceof EventHistory) {
            return $this->historyRepository->getEventById($eventId);
        } else if ($modelInstance instanceof EventYummy) {
            return $this->yummyRepository->getEventById($eventId);
        }

        return null;
    }
}
