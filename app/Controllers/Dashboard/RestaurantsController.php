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

    public function deleteRestaurant(): void
    {
        $restaurantId = $_POST['id'] ?? null;

        if (!$restaurantId) $this->redirectToRestaurants(false, 'Invalid restaurant ID.');

        $success = (bool) $this->restaurantRepository->deleteRestaurant($restaurantId);
        $this->redirectToRestaurants($success, $success ? 'Restaurant deleted successfully.' : 'Failed to delete restaurant.');
    }

    public function editRestaurant(): string
    {
        $restaurantId = $_GET['id'] ?? null;
        if (!$restaurantId) $this->redirectToRestaurants(false, 'Invalid restaurant ID.');

        $restaurant = $this->restaurantRepository->getRestaurantById($restaurantId);
        if (!$restaurant) $this->redirectToRestaurants(false, 'Restaurant not found.');

        $restaurantCover = $this->assetService->resolveAssets($restaurant, 'cover');
        $restaurantIcon = $this->assetService->resolveAssets($restaurant, 'icon');

        $formData = [
            'id' => $restaurant->id,
            'restaurant_type' => $restaurant->restaurant_type,
            'rating' => $restaurant->rating,
            'location_id' => $restaurant->location_id,
            'menu' => $restaurant->menu,
            'cover' => isset($restaurantCover[0]) ? $restaurantCover[0]->getUrl() : '',
            'icon' => isset($restaurantIcon[0]) ? $restaurantIcon[0]->getUrl() : '',
        ];

        return $this->showRestaurantForm('edit', $formData);
    }

    public function editRestaurantPost(): void
    {
        try {
            $restaurantId = $_POST['id'] ?? null;
            if (!$restaurantId) throw new \Exception('Restaurant not found.');

            $existingRestaurant = $this->restaurantRepository->getRestaurantById($restaurantId);
            if (!$existingRestaurant) throw new \Exception('Restaurant not found.');

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

            $this->redirectToRestaurants(true, 'Restaurant updated successfully.');
        } catch (\Exception $e) {
            $this->showRestaurantForm('edit', $_POST, ['Error: ' . $e->getMessage()]);
        }
    }

    public function createRestaurant(): string 
    {
        return $this->showRestaurantForm();
    }

    public function createRestaurantPost(): string
    {
        try {
            $validator = new Validator();
            $validation = $validator->validate(
                $_POST + $_FILES,
                [
                    'restaurant_logo' => 'required|uploaded_file|max:5M|mimes:jpeg,png',
                    'restaurant_icon' => 'required|uploaded_file|max:5M|mimes:jpeg,png',
                    'location_id' => 'required|integer',
                    'rating' => 'nullable|numeric|min:0|max:5',
                    'restaurant_type' => 'nullable|max:100',
                    'menu' => 'nullable|max:5000',
                ]
            );

            if ($validation->fails()) {
                return $this->showRestaurantForm('create', $_POST, $validation->errors()->all());
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
            return $this->showRestaurantForm('create', $_POST, ['Error: ' . $e->getMessage()]);
        }
    }

    private function redirectToRestaurants(bool $success = false, string $message = ''): void
    {
        $this->redirectTo('restaurants', $success, $message);
    }

    public function showRestaurantForm(string $mode = 'create', array $formData = [], array $errors = [], array $status = []): string
    {
        return $this->showForm(
            'restaurant',
            $mode,
            $formData,
            $errors,
            $status,
            ['locations' => $this->locationRepository->getAllLocations()]
        );
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
