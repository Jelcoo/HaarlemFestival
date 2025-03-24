<?php

namespace App\Controllers\Dashboard;

use App\Services\AssetService;
use Rakit\Validation\Validator;
use App\Repositories\LocationRepository;
use App\Repositories\RestaurantRepository;

class RestaurantsController extends DashboardController
{
    private RestaurantRepository $restaurantRepository;
    private LocationRepository $locationRepository;
    private AssetService $assetService;

    public function __construct()
    {
        parent::__construct();
        $this->restaurantRepository = new RestaurantRepository();
        $this->locationRepository = new LocationRepository();
        $this->assetService = new AssetService();
    }

    public function index(): string
    {
        $sortColumn = $_GET['sort'] ?? 'id';
        $sortDirection = $_GET['direction'] ?? 'asc';
        $searchQuery = $_GET['search'] ?? '';

        if (isset($_GET['search']) && $searchQuery === '') {
            $this->redirectToRestaurants();
        }

        if (!empty($_SESSION['show_restaurant_form'])) {
            unset($_SESSION['show_restaurant_form']);

            $formData = $_SESSION['form_data'] ?? [];
            unset($_SESSION['form_data']);

            return $this->renderPage(
                '/../../../components/dashboard/forms/restaurant_form',
                [
                    'locations' => $this->locationRepository->getAllLocations(),
                    'formData' => $formData,
                    'status' => $this->getStatus(),
                ]
            );
        }

        return $this->renderPage(
            'restaurants',
            [
                'restaurants' => $this->restaurantRepository->getSortedRestaurants($searchQuery, $sortColumn, $sortDirection),
                'locations' => $this->locationRepository->getAllLocations(),
                'status' => $this->getStatus(),
                'sortColumn' => $sortColumn,
                'sortDirection' => $sortDirection,
                'searchQuery' => $searchQuery,
            ]
        );
    }

    public function handleAction(): void
    {
        $action = $_POST['action'] ?? null;
        $restaurantId = $_POST['id'] ?? null;

        match ($action) {
            'delete' => $restaurantId ? $this->deleteRestaurant($restaurantId) : $this->redirectToRestaurants(false, 'Invalid restaurant ID.'),
            'update' => $restaurantId ? $this->updateRestaurant($restaurantId) : $this->redirectToRestaurants(false, 'Invalid restaurant ID.'),
            'edit' => $restaurantId ? $this->editRestaurant() : $this->redirectToRestaurants(false, 'Invalid restaurant ID.'),
            'create' => $this->showForm(),
            'createNewRestaurant' => $this->createNewRestaurant(),
            'export' => $this->exportRestaurants(),
            default => $this->redirectToRestaurants(false, 'Invalid action.'),
        };
    }

    private function deleteRestaurant(int $restaurantId): void
    {
        $deletedRestaurant = $this->restaurantRepository->deleteRestaurant($restaurantId);

        if (!is_null($deletedRestaurant)) {
            $restaurantAssets = $this->assetService->resolveAssets($deletedRestaurant);
            foreach ($restaurantAssets as $asset) {
                $this->assetService->deleteAsset($asset);
            }
        }

        $this->redirectToRestaurants(!empty($deletedRestaurant), $deletedRestaurant ? 'Restaurant deleted successfully.' : 'Failed to delete Restaurant');
    }

    private function editRestaurant(): void
    {
        try {
            $restaurantId = $_POST['id'] ?? null;
            if (!$restaurantId) {
                throw new \Exception('Invalid location ID');
            }

            $existingRestaurant = $this->restaurantRepository->getRestaurantById($restaurantId);
            if (!$existingRestaurant) {
                throw new \Exception('Restaurant not found.');
            }

            $restaurantCover = $this->assetService->resolveAssets($existingRestaurant, 'cover');
            $restaurantIcon = $this->assetService->resolveAssets($existingRestaurant, 'icon');

            $_SESSION['show_restaurant_form'] = true;
            $_SESSION['form_data'] = [
                'id' => $existingRestaurant->id,
                'restaurant_type' => $existingRestaurant->restaurant_type,
                'rating' => $existingRestaurant->rating,
                'location_id' => $existingRestaurant->location_id,
                'menu' => $existingRestaurant->menu,
                'cover' => $restaurantCover[0]->getUrl(),
                'icon' => $restaurantIcon[0]->getUrl(),
            ];

            $this->redirectToRestaurants();
        } catch (\Exception $e) {
            $_SESSION['form_data'] = $_POST;
            $_SESSION['form_errors'] = ['Error: ' . $e->getMessage()];
            $this->redirectToRestaurants(false, $e->getMessage());
        }
    }

    private function updateRestaurant(int $restaurantId): void
    {
        try {
            $existingRestaurant = $this->restaurantRepository->getRestaurantById($restaurantId);

            if (!$existingRestaurant) {
                throw new \Exception('Restaurant not found.');
            }

            $validator = new Validator();
            $validation = $validator->validate(
                $_POST + $_FILES,
                [
                    'restaurant_logo' => 'required|uploaded_file|max:5M|mimes:jpeg,png',
                    'restaurant_icon' => 'required|uploaded_file|max:5M|mimes:jpeg,png',
                    'restaurant_type' => 'nullable|max:100',
                    'rating' => 'nullable|numeric|min:0|max:5',
                    'location_id' => 'required|integer',
                    'menu' => 'nullable|max:5000',
                ]
            );

            if ($validation->fails()) {
                $_SESSION['form_data'] = $_POST;
                throw new \Exception(implode(' ', $validation->errors()->all()));
            }

            $existingRestaurant->restaurant_type = $_POST['restaurant_type'] ?? null;
            $existingRestaurant->rating = $_POST['rating'] ?? null;
            $existingRestaurant->location_id = (int) $_POST['location_id'];
            $existingRestaurant->menu = $_POST['menu'] ?? null;

            $this->restaurantRepository->updateRestaurant($existingRestaurant);

            $restaurantAssets = $this->assetService->resolveAssets($existingRestaurant);
            foreach ($restaurantAssets as $asset) {
                $this->assetService->deleteAsset($asset);
            }

            $this->assetService->saveAsset($_FILES['restaurant_logo'], 'cover', $existingRestaurant);
            $this->assetService->saveAsset($_FILES['restaurant_icon'], 'icon', $existingRestaurant);

            $this->redirectTo("restaurants?details=$restaurantId", true, 'Restaurant updated successfully.');
        } catch (\Exception $e) {
            $_SESSION['form_data'] = $_POST;
            $_SESSION['form_errors'] = ['Error: ' . $e->getMessage()];
            $this->redirectTo("restaurants?details=$restaurantId", false, $e->getMessage());
        }
    }

    private function createNewRestaurant(): void
    {
        try {
            $validator = new Validator();
            $validation = $validator->validate(
                $_POST,
                [
                    'location_id' => 'required|integer',
                    'rating' => 'nullable|numeric|min:0|max:5',
                    'restaurant_type' => 'nullable|max:100',
                    'menu' => 'nullable',
                ]
            );

            if ($validation->fails()) {
                $_SESSION['show_restaurant_form'] = true;
                $_SESSION['form_data'] = $_POST;
                throw new \Exception(implode(' ', $validation->errors()->all()));
            }

            $restaurantData = array_intersect_key(
                $_POST,
                array_flip(
                    [
                        'location_id',
                        'rating',
                        'restaurant_type',
                        'menu',
                    ]
                )
            );

            $newRestaurant = $this->restaurantRepository->createRestaurant($restaurantData);

            $this->assetService->saveAsset($_FILES['restaurant_logo'], 'cover', $newRestaurant);
            $this->assetService->saveAsset($_FILES['restaurant_icon'], 'icon', $newRestaurant);

            $this->redirectToRestaurants(true, 'Restaurant created successfully.');
        } catch (\Exception $e) {
            $_SESSION['show_restaurant_form'] = true;
            $_SESSION['form_data'] = $_POST;
            $_SESSION['form_errors'] = ['Error: ' . $e->getMessage()];
            $this->redirectToRestaurants(false, $e->getMessage());
        }
    }

    private function redirectToRestaurants(bool $success = false, string $message = ''): void
    {
        $this->redirectTo('restaurants', $success, $message);
    }

    private function showForm(): void
    {
        $_SESSION['show_restaurant_form'] = true;
        $this->redirectToRestaurants();
    }

    private function exportRestaurants(): void
    {
        $restaurants = $this->restaurantRepository->getAllRestaurantsWithLocations();

        $columns = [
            'id' => 'ID',
            'restaurant_type' => 'Restaurant Type',
            'rating' => 'Rating',
            'menu' => 'Menu',
            'location.name' => 'Location Name',
            'location.address' => 'Location Address',
        ];

        $this->exportToCsv('restaurants', $restaurants, $columns);
    }
}
