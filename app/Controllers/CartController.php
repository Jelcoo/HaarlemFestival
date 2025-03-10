<?php

namespace App\Controllers;

use App\Repositories\OrderRepository;

class CartController extends Controller
{
    private OrderRepository $orderRepository;

    public function __construct()
    {
        parent::__construct();
        $this->orderRepository = new OrderRepository();
    }
    public function index()
    {
        return $this->pageLoader->setPage('cart/index')->render();
    }
    public function createOrder()
    {
        $data = file_get_contents('php://input');
        $json = json_decode($data, true);
        $this->orderRepository->createOrder($json);
    }
}
