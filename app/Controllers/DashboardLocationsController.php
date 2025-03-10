<?php

namespace App\Controllers;

use App\Enum\EventTypeEnum;
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

        if (!empty($_SESSION['show_location_form'])) {
            unset($_SESSION['show_location_form']);

            $formData = $_SESSION['form_data'] ?? [];
            unset($_SESSION['form_data']);

            return $this->renderPage('location_form', [
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
            'create' => $this->showForm(),
            'edit' => $locationId ? $this->editLocation() : $this->redirectToLocations(false, 'Invalid location ID.'),
            'createLocation' => $this->createNewLocation(),
            default => $this->redirectToLocations(false, 'Invalid action.'),
        };
    }

    private function deleteLocation(int $locationId): void
    {
        $success = $this->locationRepository->deleteLocation($locationId);
        $this->redirectToLocations(!empty($success), $success ? 'Location deleted successfully.' : 'Failed to delete Location');
    }

    private function editLocation(): void
    {
        try {
            $locationId = $_POST['id'] ?? null;
            if (!$locationId) {
                throw new \Exception('Invalid location ID.');
            }

            $existingLocation = $this->locationRepository->getLocationById($locationId);
            if (!$existingLocation) {
                throw new \Exception('Location not found.');
            }

            $_SESSION['show_location_form'] = true;
            $_SESSION['form_data'] = [
                'id' => $existingLocation->id,
                'name' => $existingLocation->name,
                'event_type' => $existingLocation->event_type->value,
                'address' => $existingLocation->address,
                'coordinates' => $existingLocation->coordinates,
                'preview_description' => $existingLocation->preview_description,
                'main_description' => $existingLocation->main_description,
            ];

            $this->redirectToLocations();
        } catch (\Exception $e) {
            $_SESSION['form_data'] = $_POST;
            $_SESSION['form_errors'] = ['Error: ' . $e->getMessage()];
            $this->redirectToLocations(false, $e->getMessage());
        }
    }

    private function updateLocation(int $locationId): void
    {
        try {
            $existingLocation = $this->locationRepository->getLocationById($locationId);

            if (!$existingLocation) {
                $this->redirectToLocations(false, 'Location not found');
                return;
            }

            $validator = new Validator();
            $validation = $validator->validate($_POST, [
                'name' => 'required|max:255',
                'event_type' => 'required|in:dance,yummy,history,teylers',
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

            if (!isset($_POST['event_type']) || !in_array($_POST['event_type'], array_column(EventTypeEnum::cases(), 'value'))) {
                throw new \Exception('Invalid or missing event type.');
            }

            $existingLocation->name = $_POST['name'];
            $existingLocation->event_type = EventTypeEnum::from($_POST['event_type']);
            $existingLocation->coordinates = $_POST['coordinates'] ?? null;
            $existingLocation->address = $_POST['address'] ?? null;
            $existingLocation->preview_description = $_POST['preview_description'] ?? null;
            $existingLocation->main_description = $_POST['main_description'] ?? null;

            $this->locationRepository->updateLocation($existingLocation);
            $this->redirectTo("locations?details=$locationId", true, 'Location updated successfully');
        } catch (\Exception $e) {
            $_SESSION['form_data'] = $_POST;
            $_SESSION['form_errors'] = ['Error: ' . $e->getMessage()];
            $this->redirectToLocations(false, $e->getMessage());
        }
    }

    private function createNewLocation(): void
    {
        try {
            $validator = new Validator();
            $validation = $validator->validate($_POST, [
                'name' => 'required|max:255',
                'event_type' => 'required',
                'address' => 'required|max:255',
                'coordinates' => 'nullable|regex:/^-?\d{1,3}\.\d+,\s*-?\d{1,3}\.\d+$/',
                'preview_description' => 'nullable|max:500',
                'main_description' => 'nullable|max:2000',
            ]);

            if ($validation->fails()) {
                $_SESSION['show_location_form'] = true;
                $_SESSION['form_data'] = $_POST;
                throw new \Exception(implode(' ', $validation->errors()->all()));
            }

            if (!isset($_POST['event_type']) || !in_array($_POST['event_type'], array_column(EventTypeEnum::cases(), 'value'))) {
                throw new \Exception('Invalid or missing event type.');
            }

            $locationData = array_intersect_key($_POST, array_flip([
                'name',
                'event_type',
                'address',
                'coordinates',
                'preview_description',
                'main_description'
            ]));

            $createdLocation = $this->locationRepository->createLocation($locationData);
            $this->redirectToLocations(!empty($createdLocation), "Location '{$locationData['name']}' created successfully.");
        } catch (\Exception $e) {
            $_SESSION['show_location_form'] = true;
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
            'event_type' => ['label' => 'Event Type', 'sortable' => true],
            'coordinates' => ['label' => 'Coordinates', 'sortable' => false],
            'address' => ['label' => 'Address', 'sortable' => false],
            'preview_description' => ['label' => 'Preview Description', 'sortable' => false],
            'main_description' => ['label' => 'Main Description', 'sortable' => false],
            'actions' => ['label' => 'Actions', 'sortable' => false],
        ];
    }

    private function redirectToLocations(bool $success = false, string $message = ''): void
    {
        $this->redirectTo('locations', $success, $message);
    }

    private function showForm(): void
    {
        $_SESSION['show_location_form'] = true;
        $this->redirectToLocations();
    }
}
