<?php

namespace App\Controllers;

use App\Services\ScheduleService;
use App\Repositories\ArtistRepository;
use App\Repositories\LocationRepository;

class HomeController extends Controller
{
    private LocationRepository $locationRepository;
    private ArtistRepository $artistRepository;
    private ScheduleService $scheduleService;

    public function __construct()
    {
        parent::__construct();

        $this->locationRepository = new LocationRepository();
        $this->artistRepository = new ArtistRepository();
        $this->scheduleService = new ScheduleService();
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
        $schedules = $this->scheduleService->getDanceSchedule();

        return $this->pageLoader->setPage('dance')->render([
            'artists' => $artists,
            'locations' => $locations,
            'schedules' => $schedules,
        ]);
    }

    public function yummy(): string
    {
        $restaurants = $this->locationRepository->getYummyLocations();

        return $this->pageLoader->setPage('yummy')->render([
            'restaurants' => $restaurants,
        ]);
    }

    public function history(): string
    {
        $locations = $this->locationRepository->getSpecificLocations('history');
        $schedules = $this->scheduleService->getHistorySchedule();

        return $this->pageLoader->setPage('history')->render([
            'locations' => $locations,
            'schedules' => $schedules,
        ]);
    }

    public function magic(): string
    {
        return $this->pageLoader->setPage('magic')->render();
    }
}
