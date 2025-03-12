<?php

namespace App\Controllers;

class CheckoutController extends Controller
{
    public function index(array $paramaters = [])
    {
        return $this->pageLoader->setPage('checkout/index')->render($paramaters);
    }
    public function payLater(array $paramaters = [])
    {
        return $this->pageLoader->setPage('checkout/pay_later')->render($paramaters);
    }
}