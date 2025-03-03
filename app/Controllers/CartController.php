<?php

namespace App\Controllers;

class CartController extends Controller
{
    public function index()
    {
        return $this->pageLoader->setPage('cart/index')->render();
    }
}
