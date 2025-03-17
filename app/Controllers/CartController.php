<?php

namespace App\Controllers;

use App\Application\Response;
use App\Services\CartService;
use App\Services\OrderService;

class CartController extends Controller
{
    private OrderService $orderService;
    private CartService $cartService;

    public function __construct()
    {
        parent::__construct();
        $this->orderService = new OrderService();
        $this->cartService = new CartService();
    }

    public function index(array $paramaters = [])
    {
        $cart = $this->cartService->getSessionCart(true);

        return $this->pageLoader->setPage('cart/index')->render([
            'cartItems' => $cart->items
        ]);
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
