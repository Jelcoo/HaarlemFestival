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

    public function dance(): string
    {
        return $this->pageLoader->setPage('dance')->render();
    }

    public function yummy(): string
    {
        return $this->pageLoader->setPage('yummy')->render();
    }

    public function history(): string
    {
        return $this->pageLoader->setPage('history')->render();
    }

    public function magic(): string
    {
        return $this->pageLoader->setPage('magic')->render();
    }
}
