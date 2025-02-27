<?php

namespace App\Controllers;

use App\Repositories\LocationRepository;

class DashboardLocationsController extends DashboardController
{
    private LocationRepository $locationRepository;

    public function __construct()
    {
        parent::__construct();
        $this->locationRepository = new LocationRepository();
    }

    public function index(): string
    {
        $sortColumn = $_GET['sort'] ?? 'id';
        $sortDirection = $_GET['direction'] ?? 'asc';
        $searchQuery = $_GET['search'] ?? '';

        if (isset($_GET['search']) && $searchQuery === '') {
            $this->redirectToLocations();
        }

        // if (!empty($_SESSION['show_create_restaurant_form'])) {
        //     unset($_SESSION['show_create_restaurant_form']);

        //     return $this->renderPage('restaurant_create', [
        //         'locations' => $locations,
        //     ]);
        // }

        return $this->renderPage('locations', [
            'locations' => $this->locationRepository->getSortedLocations($searchQuery, $sortColumn, $sortDirection),
            'status' => $this->getStatus(),
            'columns' => $this->getColumns(),
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
            'searchQuery' => $searchQuery,
        ]);
    }

    private function getColumns(): array
    {
        return [
            'id' => ['label' => 'ID', 'sortable' => true],
            'name' => ['label' => 'Location Name', 'sortable' => true],
            'coordinates' => ['label' => 'Coordinates', 'sortable' => false],
            'address' => ['label' => 'Address', 'sortable' => true],
            'preview_description' => ['label' => 'Preview Description', 'sortable' => false],
            'main_description' => ['label' => 'Main Description', 'sortable' => false],
            'actions' => ['label' => 'Actions', 'sortable' => false],
        ];
    }

    private function redirectToLocations(bool $success = false, string $message = ''): void
    {
        $this->redirectTo('locations', $success, $message);
    }
}