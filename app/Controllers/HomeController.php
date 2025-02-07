<?php

namespace App\Controllers;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(): string
    {
        return $this->pageLoader->setPage('home')->render();
    }
}
