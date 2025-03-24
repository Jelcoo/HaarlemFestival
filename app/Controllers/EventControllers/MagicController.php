<?php

namespace App\Controllers\EventControllers;

use App\Controllers\Controller;

class MagicController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function show(): string
    {
        return $this->pageLoader->setPage('magic')->render();
    }
}
