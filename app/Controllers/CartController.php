<?php

namespace App\Controllers;

use App\Application\Response;
use App\Services\OrderService;

class CartController extends Controller
{
    private OrderService $orderService;

    public function __construct()
    {
        parent::__construct();
        $this->orderService = new OrderService();
    }

    public function index(array $paramaters = [])
    {
        $_SESSION['cart'] = true;

        return $this->pageLoader->setPage('cart/index')->render($paramaters);
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
