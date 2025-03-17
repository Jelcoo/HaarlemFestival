<?php

namespace App\Services;

use App\Models\Cart;
use App\Repositories\CartRepository;

class CartService
{
    private CartRepository $cartRepository;

    public function __construct()
    {
        $this->cartRepository = new CartRepository();
    }

    public function getSessionCart(bool $includeItems = false, bool $includeEvents = false): Cart
    {
        $userId = $_SESSION['user_id'] ?? null;

        if (!isset($_SESSION['cart_id'])) {
            $cart = $this->cartRepository->createCart($userId);
            $_SESSION['cart_id'] = $cart->id;

            return $cart;
        }

        $cart = $this->cartRepository->getCartById($_SESSION['cart_id'], $includeItems, $includeEvents);

        if (is_null($cart)) {
            unset($_SESSION['cart_id']);

            return $this->getSessionCart();
        }

        return $cart;
    }
}
