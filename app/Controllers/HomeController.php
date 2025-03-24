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
        $locations = $this->locationRepository->getHomeLocations();

        return $this->pageLoader->setPage('home')->render([
            'locations' => $locations,
        ]);
    }
}
