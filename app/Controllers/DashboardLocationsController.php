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

    public function handleAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $action = $_POST['action'] ?? null;
        $locationId = $_POST['id'] ?? null;

        match ($action) {
            'delete' => $locationId ? $this->deleteLocation($locationId) : $this->redirectToLocations(false, 'Invalid location ID.'),
            'update' => $locationId ? $this->updateLocation($locationId) : $this->redirectToLocations(false, 'Invalid Location ID.'),
            // 'create' => $this->showCreateRestaurantForm(),
            // 'createNewRestaurant' => $this->createNewRestaurant(),
            default => $this->redirectToLocations(false, 'Invalid action.'),
        };
    }

    private function deleteLocation(int $locationId): void
    {
        $success = $this->locationRepository->deleteLocation($locationId);
        $this->redirectToLocations(!empty($success), $success ? 'Location deleted successfully.' : 'Failed to delete Location');
    }

    private function updateLocation(int $locationId): void
    {
        $existingLocation = $this->locationRepository->getLocationById($locationId);

        if (!$existingLocation) {
            $this->redirectToLocations(false, 'Location not found');
            return;
        }

        $fieldsToUpdate = [
            'name' => $_POST['name'] ?? $existingLocation->name,
            'coordinates' => $_POST['coordinates'] ?? $existingLocation->coordinates,
            'address' => $_POST['address'] ?? $existingLocation->address,
            'preview_description' => $_POST['preview_description'] ?? $existingLocation->preview_description,
            'main_description' => $_POST['main_description'] ?? $existingLocation->main_description,
        ];

        foreach ($fieldsToUpdate as $field => $value) {
            $existingLocation->$field = $value;
        }

        $updatedLocation = $this->locationRepository->updateLocation($existingLocation);
        $this->redirectToLocations(!empty($updatedLocation), $updatedLocation ? 'Location updated successfully.' : 'No changes were made.');
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
