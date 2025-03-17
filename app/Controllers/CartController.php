<?php

namespace App\Controllers;

use App\Application\Response;
use App\Repositories\CartRepository;
use App\Services\CartService;
use App\Services\OrderService;

class CartController extends Controller
{
    private OrderService $orderService;
    private CartService $cartService;
    private CartRepository $cartRepository;

    public function __construct()
    {
        parent::__construct();
        $this->orderService = new OrderService();
        $this->cartService = new CartService();
        $this->cartRepository = new CartRepository();
    }

    public function index(array $paramaters = [])
    {
        $cart = $this->cartService->getSessionCart(true, true);

        return $this->pageLoader->setPage('cart/index')->render([
            'cartItems' => $cart->items
        ]);
    }

    public function increaseQuantity(array $paramaters = [])
    {
        $cart = $this->cartService->getSessionCart();

        $this->cartRepository->increaseQuantity($cart->id, $_POST['item_id']);

        Response::redirect('/cart');
    }

    public function decreaseQuantity(array $paramaters = [])
    {
        $cart = $this->cartService->getSessionCart();

        $this->cartRepository->decreaseQuantity($cart->id, $_POST['item_id']);

        Response::redirect('/cart');
    }

    public function removeItem(array $paramaters = [])
    {
        $cart = $this->cartService->getSessionCart();

        $this->cartRepository->deleteCartItem($cart->id, $_POST['item_id']);

        Response::redirect('/cart');
    }

    public function checkout()
    {
        $result = $this->orderService->validateAvailability($_POST['order']);
        if (isset($result['error'])) {
            return $this->pageLoader->setPage('cart/index')->render($result);
        }
        if ($_POST['paymentChoice'] == 'payNow') {
            Response::redirect('/checkout');
        } else {
            $this->orderService->createOrder($_POST['order']);
            Response::redirect('/checkout/pay_later');
        }
    }
}
