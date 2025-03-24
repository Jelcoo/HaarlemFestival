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
        $sessionCartId = $_SESSION['cart_id'] ?? null;

        // Get user's cart if logged in
        $userCart = $userId ? $this->cartRepository->getCartByUserId($userId, $includeItems, $includeEvents) : null;

        // Get session cart if exists
        $sessionCart = $sessionCartId ? $this->cartRepository->getCartById($sessionCartId, $includeItems, $includeEvents) : null;

        // If user is logged in and has a cart
        if ($userId && $userCart) {
            // If session has a cart with items, merge them
            if ($sessionCart && count($sessionCart->items) > 0) {
                return $this->mergeCarts($userCart, $sessionCart);
            }

            // Return user's cart if it has items, otherwise return session cart or new cart
            return count($userCart->items) > 0 ? $userCart : ($sessionCart ?? $this->cartRepository->createCart($userId));
        }

        // If user is not logged in or has no cart, return session cart or create new one
        if ($sessionCart) {
            return $sessionCart;
        }

        // Create new cart
        return $this->cartRepository->createCart($userId);
    }

    private function mergeCarts(Cart $userCart, Cart $sessionCart): Cart
    {
        // Move all items from session cart to user cart
        foreach ($sessionCart->items as $item) {
            $this->cartRepository->moveCartItem($item->id, $userCart->id);
        }

        // Delete the session cart
        $this->cartRepository->deleteCart($sessionCart->id);

        // Update session to use user's cart
        $_SESSION['cart_id'] = $userCart->id;

        return $userCart;
    }
}
