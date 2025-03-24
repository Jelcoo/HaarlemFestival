<?php

namespace App\Controllers\Dashboard;

use App\Enum\EventTypeEnum;
use App\Services\AssetService;
use Rakit\Validation\Validator;
use App\Repositories\LocationRepository;

class LocationsController extends DashboardController
{
    private LocationRepository $locationRepository;
    private AssetService $assetService;

    public function __construct()
    {
        parent::__construct();
        $this->locationRepository = new LocationRepository();
        $this->assetService = new AssetService();
    }

    public function index(): string
    {
        $sortColumn = $_GET['sort'] ?? 'id';
        $sortDirection = $_GET['direction'] ?? 'asc';
        $searchQuery = $_GET['search'] ?? '';

        if (isset($_GET['search']) && $searchQuery === '') {
            $this->redirectToLocations();
        }

        return $this->renderPage(
            'locations',
            [
                'locations' => $this->locationRepository->getSortedLocations($searchQuery, $sortColumn, $sortDirection),
                'status' => $this->getStatus(),
                'sortColumn' => $sortColumn,
                'sortDirection' => $sortDirection,
                'searchQuery' => $searchQuery,
            ]
        );
    }

    public function deleteLocation(): void
    {
        $locationId = $_POST['id'] ?? null;

        if (!$locationId) {
            $this->redirectToLocations(false, 'Invalid location ID.');
        }

        $success = (bool) $this->locationRepository->deleteLocation($locationId);
        $this->redirectToLocations($success, $success ? 'Location deleted successfully.' : 'Failed to delete Location');
    }

    public function editLocation(): string
    {
        $locationId = $_GET['id'] ?? null;
        if (!$locationId) {
            $this->redirectToLocations(false, 'Invalid location ID.');
        }

        $existingLocation = $this->locationRepository->getLocationById($locationId);
        if (!$existingLocation) {
            $this->redirectToLocations(false, 'Location not found.');
        }

        $locationCover = $this->assetService->resolveAssets($existingLocation, 'cover');

        $formData = [
            'id' => $existingLocation->id,
            'name' => $existingLocation->name,
            'event_type' => $existingLocation->event_type->value,
            'address' => $existingLocation->address,
            'coordinates' => $existingLocation->coordinates,
            'preview_description' => $existingLocation->preview_description,
            'main_description' => $existingLocation->main_description,
            'cover' => count($locationCover) > 0 ? $locationCover[0]->getUrl() : null,
        ];

        return $this->showLocationForm('edit', $formData);
    }

    public function editLocationPost()
    {
        try {
            $locationId = $_POST['id'] ?? null;
            if (!$locationId) {
                throw new \Exception('Location not found.');
            }

            $existingLocation = $this->locationRepository->getLocationById($locationId);
            if (!$existingLocation) {
                throw new \Exception('Location not found.');
            }

            $validator = new Validator();
            $validation = $validator->validate(
                $_POST + $_FILES,
                [
                    'location_cover' => 'nullable|uploaded_file|max:5M|mimes:jpeg,png',
                    'name' => 'required|max:255',
                    'event_type' => 'required|in:dance,yummy,history,teylers',
                    'address' => 'required|max:255',
                    'coordinates' => 'nullable|regex:/^-?\d{1,3}\.\d+,\s*-?\d{1,3}\.\d+$/',
                    'preview_description' => 'nullable|max:500',
                    'main_description' => 'nullable|max:2000',
                ]
            );

            if ($validation->fails()) {
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

            $locationAssets = $this->assetService->resolveAssets($existingLocation);
            foreach ($locationAssets as $asset) {
                $this->assetService->deleteAsset($asset);
            }

            if (!empty($_FILES['location_cover']['name'])) {
                $this->assetService->saveAsset($_FILES['location_cover'], 'cover', $existingLocation);
            }

            $this->redirectToLocations(true, 'Location updated successfully');
        } catch (\Exception $e) {
            $this->showLocationForm('edit', $_POST, ['Error: ' . $e->getMessage()]);
        }
    }

    public function createLocation(): string
    {
        return $this->showLocationForm();
    }

    public function createLocationPost(): void
    {
        try {
            $validator = new Validator();
            $validation = $validator->validate(
                $_POST + $_FILES,
                [
                    'location_cover' => 'nullable|uploaded_file|max:5M|mimes:jpeg,png',
                    'name' => 'required|max:255',
                    'event_type' => 'required',
                    'address' => 'required|max:255',
                    'coordinates' => 'nullable|regex:/^-?\d{1,3}\.\d+,\s*-?\d{1,3}\.\d+$/',
                    'preview_description' => 'nullable|max:500',
                    'main_description' => 'nullable|max:2000',
                ]
            );

            if ($validation->fails()) {
                $_SESSION['show_location_form'] = true;
                $_SESSION['form_data'] = $_POST;
                throw new \Exception(implode(' ', $validation->errors()->all()));
            }

            if (!isset($_POST['event_type']) || !in_array($_POST['event_type'], array_column(EventTypeEnum::cases(), 'value'))) {
                throw new \Exception('Invalid or missing event type.');
            }

            $locationData = array_intersect_key(
                $_POST,
                array_flip(
                    [
                        'name',
                        'event_type',
                        'address',
                        'coordinates',
                        'preview_description',
                        'main_description',
                    ]
                )
            );

            $newLocation = $this->locationRepository->createLocation($locationData);

            if (!empty($_FILES['location_cover']['name'])) {
                $this->assetService->saveAsset($_FILES['location_cover'], 'cover', $newLocation);
            }

            $this->redirectToLocations(true, 'Location created successfully.');
        } catch (\Exception $e) {
            $_SESSION['show_location_form'] = true;
            $_SESSION['form_data'] = $_POST;
            $_SESSION['form_errors'] = ['Error: ' . $e->getMessage()];
            $this->redirectToLocations(false, $e->getMessage());
        }
    }

    private function redirectToLocations(bool $success = false, string $message = ''): void
    {
        $this->redirectTo('locations', $success, $message);
    }

    public function showLocationForm(string $mode = 'create', array $formData = [], array $errors = [], array $status = []): string
    {
        return $this->showForm(
            'location',
            $mode,
            $formData,
            $errors,
            $status
        );
    }

    public function exportLocations(): void
    {
        $locations = $this->locationRepository->getAllLocations();

        $columns = [
            'id' => 'ID',
            'name' => 'Name',
            'event_type' => 'Event Type',
            'coordinates' => 'Coordinates',
            'address' => 'Address',
            'preview_description' => 'Preview Description',
            'main_description' => 'Main Description',
        ];

        $this->exportToCsv('locations', $locations, $columns);
    }
}
