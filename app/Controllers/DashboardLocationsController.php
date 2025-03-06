<?php

namespace App\Controllers;

use Rakit\Validation\Validator;
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

        if (!empty($_SESSION['show_create_location_form'])) {
            unset($_SESSION['show_create_location_form']);

            $formData = $_SESSION['form_data'] ?? [];
            unset($_SESSION['form_data']);

            return $this->renderPage('location_create', [
                'formData' => $formData,
                'status' => $this->getStatus(),
            ]);
        }

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
            'create' => $this->showCreateLocationForm(),
            'createNewLocation' => $this->createNewLocation(),
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

    private function createNewLocation(): void
    {
        try {
            $validator = new Validator();
            $validation = $validator->validate($_POST, [
                'name' => 'required|max:255',
                'address' => 'required|max:255',
                'coordinates' => 'nullable|regex:/^-?\d{1,3}\.\d+,\s*-?\d{1,3}\.\d+$/',
                'preview_description' => 'nullable|max:500',
                'main_description' => 'nullable|max:2000',
            ]);

            if ($validation->fails()) {
                $_SESSION['show_create_location_form'] = true;
                $_SESSION['form_data'] = $_POST;
                throw new \Exception(implode(' ', $validation->errors()->all()));
            }

            $locationData = array_intersect_key($_POST, array_flip([
                'name',
                'address',
                'coordinates',
                'preview_description',
                'main_description'
            ]));

            $createdLocation = $this->locationRepository->createLocation($locationData);
            $this->redirectToLocations(!empty($createdLocation), "Location '{$locationData['name']}' created successfully.");
        } catch (\Exception $e) {
            $_SESSION['show_create_location_form'] = true;
            $_SESSION['form_data'] = $_POST;
            $_SESSION['form_errors'] = ['Error: ' . $e->getMessage()];
            $this->redirectToLocations(false, $e->getMessage());
        }
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

    private function showCreateLocationForm(): void
    {
        $_SESSION['show_create_location_form'] = true;
        $this->redirectToLocations();
    }
}
