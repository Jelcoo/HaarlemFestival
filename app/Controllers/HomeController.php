<?php

namespace App\Controllers;

use App\Repositories\ArtistRepository;
use App\Repositories\LocationRepository;

class HomeController extends Controller
{
    private LocationRepository $locationRepository;
    private ArtistRepository $artistRepository;

    public function __construct()
    {
        parent::__construct();

        $this->locationRepository = new LocationRepository();
        $this->artistRepository = new ArtistRepository();
    }

    public function index(): string
    {
        $locations = $this->locationRepository->getHomeLocations();

        return $this->pageLoader->setPage('home')->render([
            'locations' => $locations,
        ]);
    }

    public function dance(): string
    {
        $artists = $this->artistRepository->getAllArtists();
        $locations = $this->locationRepository->getSpecificLocations('dance');

        return $this->pageLoader->setPage('dance')->render([
            'artists' => $artists,
            'locations' => $locations,
        ]);
    }

    public function yummy(): string
    {
        return $this->pageLoader->setPage('yummy')->render();
    }

    public function history(): string
    {
        $locations = $this->locationRepository->getSpecificLocations('history');

        return $this->pageLoader->setPage('history')->render([
            'locations' => $locations,
        ]);
    }

    public function magic(): string
    {
        return $this->pageLoader->setPage('magic')->render();
    }
}
