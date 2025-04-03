<?php

namespace App\Controllers\EventControllers;

use App\Controllers\Controller;
use App\Repositories\LocationRepository;

class HistoryController extends Controller
{
    private LocationRepository $locationRepository;

    public function __construct()
    {
        parent::__construct();
        $this->locationRepository = new LocationRepository();
    }

    public function showMain(): string
    {
        $locations = $this->locationRepository->getSpecificLocations('history');

        return $this->pageLoader->setPage('history')->render([
            'locations' => $locations,
        ]);
    }

    public function showDetail(string $slug, int $id): string
    {
        $location = $this->locationRepository->getLocationById($id);
        if (!$location) {
            return $this->pageLoader->setPage('_404')->render();
        }

        // Expected slug based on name (does not handle special characters)
        $expectedSlug = str_replace(' ', '_', $location->name);

        // Redirect if incorrect URL
        if (urldecode($slug) !== $expectedSlug) {
            header("Location: /history/{$expectedSlug}_{$id}", true, 301);
            exit;
        }

        return $this->pageLoader->setPage('location-detail')->render([
            'location' => $location,
        ]);
    }
}
