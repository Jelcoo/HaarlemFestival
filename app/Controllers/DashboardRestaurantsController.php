<?php

namespace App\Controllers;

use App\Repositories\LocationRepository;
use App\Repositories\RestaurantRepository;

class DashboardRestaurantsController extends DashboardController
{
    private RestaurantRepository $restaurantRepository;
    private LocationRepository $locationRepository;

    public function __construct()
    {
        parent::__construct();
        $this->restaurantRepository = new RestaurantRepository();
        $this->locationRepository = new LocationRepository();
    }

    public function index(): string
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $sortColumn = $_GET['sort'] ?? 'id';
        $sortDirection = $_GET['direction'] ?? 'asc';
        $searchQuery = $_GET['search'] ?? '';

        if (isset($_GET['search']) && $searchQuery === '') {
            $this->redirectToRestaurants();
        }

        $locations = $this->locationRepository->getAllLocations();

        if (!empty($_SESSION['show_create_restaurant_form'])) {
            unset($_SESSION['show_create_restaurant_form']);

            return $this->renderPage('restaurant_create', [
                'locations' => $locations,
            ]);
        }

        return $this->renderPage('restaurants', [
            'restaurants' => $this->restaurantRepository->getSortedRestaurants($searchQuery, $sortColumn, $sortDirection),
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
        $restaurantId = $_POST['id'] ?? null;

        match ($action) {
            'delete' => $restaurantId ? $this->deleteRestaurant($restaurantId) : $this->redirectToRestaurants(false, 'Invalid restaurant ID.'),
            'update' => $restaurantId ? $this->updateRestaurant($restaurantId) : $this->redirectToRestaurants(false, 'Invalid restaurant ID.'),
            'create' => $this->showCreateRestaurantForm(),
            'createNewRestaurant' => $this->createNewRestaurant(),
            default => $this->redirectToRestaurants(false, 'Invalid action.'),
        };
    }

    private function deleteRestaurant(int $restaurantId): void
    {
        $success = $this->restaurantRepository->deleteRestaurant($restaurantId);
        $this->redirectToRestaurants(!empty($success), $success ? 'Restaurant deleted successfully.' : 'Failed to delete Restaurant');
    }

    private function updateRestaurant(int $restaurantId): void
    {
        $existingRestaurant = $this->restaurantRepository->getRestaurantById($restaurantId);

        if (!$existingRestaurant) {
            $this->redirectToRestaurants(false, 'Restaurant not found');
            return;
        }

        $fieldsToUpdate = [
            'name' => $_POST['name'] ?? $existingRestaurant->name,
            'restaurant_type' => $_POST['restaurant_type'] ?? $existingRestaurant->restaurant_type,
            'rating' => isset($_POST['rating']) ? (int)$_POST['rating'] : $existingRestaurant->rating,
            'preview_description' => $_POST['preview_description'] ?? $existingRestaurant->preview_description,
            'main_description' => $_POST['main_description'] ?? $existingRestaurant->main_description,
            'menu' => $_POST['menu'] ?? $existingRestaurant->menu,
        ];

        foreach ($fieldsToUpdate as $field => $value) {
            $existingRestaurant->$field = $value;
        }

        $updatedRestaurant = $this->restaurantRepository->updateRestaurant($existingRestaurant);
        $this->redirectToRestaurants(!empty($updatedRestaurant), $updatedRestaurant ? 'Restaurant updated successfully.' : 'No changes were made.');
    }

    private function createNewRestaurant(): void
    {
        if (empty($_POST['name']) || empty($_POST['restaurant_type']) || empty($_POST['location_id'])) {
            $this->redirectToRestaurants(false, 'Please fill in all required fields.');

            return;
        }

        $restaurant = [
            'name' => $_POST['name'],
            'restaurant_type' => $_POST['restaurant_type'],
            'rating' => isset($_POST['rating']) ? (int)$_POST['rating'] : null,
            'location_id' => (int)$_POST['location_id'],
            'preview_description' => $_POST['preview_description'] ?? '',
            'main_description' => $_POST['main_description'] ?? '',
            'menu' => $_POST['menu'] ?? '',
        ];

        $createdRestaurant = $this->restaurantRepository->createRestaurant($restaurant);
        $this->redirectToRestaurants(!empty($createdRestaurant), $createdRestaurant ? 'Restaurant created successfully.' : 'Failed to create restaurant.');
    }


    private function getColumns(): array
    {
        return [
            'id' => ['label' => 'ID', 'sortable' => true],
            'name' => ['label' => 'Name', 'sortable' => true],
            'restaurant_type' => ['label' => 'Restaurant Type', 'sortable' => true],
            'rating' => ['label' => 'Rating', 'sortable' => true],
            'location' => ['label' => 'Location', 'sortable' => true],
            'preview_description' => ['label' => 'Preview Description', 'sortable' => false],
            'main_description' => ['label' => 'Main Description', 'sortable' => false],
            'menu' => ['label' => 'Menu', 'sortable' => false],
            'actions' => ['label' => 'Actions', 'sortable' => false],
        ];
    }

    private function getStatus(): array
    {
        $status = $_SESSION['status'] ?? ['status' => false, 'message' => ''];
        unset($_SESSION['status']);

        return $status;
    }

    private function redirectToRestaurants(bool $success = false, string $message = ''): void
    {
        $this->redirectTo('restaurants', $success, $message);
    }

    private function showCreateRestaurantForm(): void
    {
        $_SESSION['show_create_restaurant_form'] = true;
        $this->redirectToRestaurants(false, '');
    }
}
