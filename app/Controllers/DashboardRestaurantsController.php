<?php

namespace App\Controllers;

use Rakit\Validation\Validator;
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

        if (!empty($_SESSION['show_create_restaurant_form'])) {
            unset($_SESSION['show_create_restaurant_form']);

            $formData = $_SESSION['form_data'] ?? [];
            unset($_SESSION['form_data']);

            return $this->renderPage('restaurant_create', [
                'locations' => $this->locationRepository->getAllLocations(),
                'formData' => $formData,
                'status' => $this->getStatus(),
            ]);
        }

        return $this->renderPage('restaurants', [
            'restaurants' => $this->restaurantRepository->getSortedRestaurants($searchQuery, $sortColumn, $sortDirection),
            'locations' => $this->locationRepository->getAllLocations(),
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
        try {
            $existingRestaurant = $this->restaurantRepository->getRestaurantById($restaurantId);

            if (!$existingRestaurant) {
                throw new \Exception('Restaurant not found.');
            }

            $validator = new Validator();
            $validation = $validator->validate($_POST, [
                'name' => 'required|alpha_spaces|max:255',
                'restaurant_type' => 'nullable|alpha_spaces|max:100',
                'rating' => 'nullable|numeric|min:0|max:5',
                'location_id' => 'required|integer',
                'preview_description' => 'nullable|max:500',
                'main_description' => 'nullable|max:2000',
                'menu' => 'nullable|max:5000',
            ]);

            if ($validation->fails()) {
                $_SESSION['form_data'] = $_POST;
                throw new \Exception(implode(' ', $validation->errors()->all()));
            }

            $fieldsToUpdate = [
                'name' => $_POST['name'] ?? $existingRestaurant->name,
                'restaurant_type' => $_POST['restaurant_type'] ?? $existingRestaurant->restaurant_type,
                'rating' => isset($_POST['rating']) ? (float)$_POST['rating'] : $existingRestaurant->rating,
                'location_id' => (int)$_POST['location_id'],
                'preview_description' => $_POST['preview_description'] ?? $existingRestaurant->preview_description,
                'main_description' => $_POST['main_description'] ?? $existingRestaurant->main_description,
                'menu' => $_POST['menu'] ?? $existingRestaurant->menu,
            ];

            foreach ($fieldsToUpdate as $field => $value) {
                $existingRestaurant->$field = $value;
            }

            $updatedRestaurant = $this->restaurantRepository->updateRestaurant($existingRestaurant);
            $this->redirectTo("restaurants?details=$restaurantId", !empty($updatedRestaurant), 'Restaurant updated successfully.');
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
            $validation = $validator->validate($_POST, [
                'name' => 'required|alpha_spaces|max:255',
                'restaurant_type' => 'nullable|alpha_spaces|max:100',
                'rating' => 'nullable|numeric|min:0|max:5',
                'location_id' => 'required|integer',
                'preview_description' => 'nullable|max:500',
                'main_description' => 'nullable|max:2000',
                'menu' => 'nullable|max:5000',
            ]);

            if ($validation->fails()) {
                $_SESSION['show_create_restaurant_form'] = true;
                $_SESSION['form_data'] = $_POST;
                throw new \Exception(implode(' ', $validation->errors()->all()));
            }

            $restaurantData = array_intersect_key($_POST, array_flip([
                'name',
                'restaurant_type',
                'rating',
                'location_id',
                'preview_description',
                'main_description',
                'menu'
            ]));

            $createdRestaurant = $this->restaurantRepository->createRestaurant($restaurantData);
            $this->redirectToRestaurants(!empty($createdRestaurant), "Restaurant '{$restaurantData['name']}' created successfully.");
        } catch (\Exception $e) {
            $_SESSION['show_create_restaurant_form'] = true;
            $_SESSION['form_data'] = $_POST;
            $_SESSION['form_errors'] = ['Error: ' . $e->getMessage()];
            $this->redirectToRestaurants(false, $e->getMessage());
        }
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
