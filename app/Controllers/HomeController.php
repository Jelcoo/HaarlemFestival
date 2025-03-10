<?php

namespace App\Controllers;

use App\Repositories\LocationRepository;

class HomeController extends Controller
{
    private LocationRepository $locationRepository;

    public function __construct()
    {
        parent::__construct();

        $this->locationRepository = new LocationRepository();
    }

    public function index(): string
    {
        $locations = $this->locationRepository->getAllLocations();

        return $this->pageLoader->setPage('home')->render([
            'locations' => $locations,
        ]);
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
