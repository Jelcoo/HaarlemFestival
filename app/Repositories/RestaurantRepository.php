<?php

namespace App\Repositories;

use App\Models\Location;
use App\Models\Restaurant;
use App\Helpers\QueryBuilder;

class RestaurantRepository extends Repository
{
    private AssetRepository $assetRepository;

    public function __construct()
    {
        parent::__construct();

        $this->assetRepository = new AssetRepository();
    }

    public function createRestaurant(array $data): Restaurant
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $restaurantId = $queryBuilder->table('restaurants')->insert($data);
        $restaurant = $this->getRestaurantById((int) $restaurantId);

        return $restaurant;
    }

    public function getRestaurantById(int $id): ?Restaurant
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryRestaurant = $queryBuilder->table('restaurants')->where('id', '=', $id)->first();

        return $queryRestaurant ? new Restaurant($queryRestaurant) : null;
    }

    public function getAllRestaurants(): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryRestaurants = $queryBuilder->table('restaurants')->get();

        return $queryRestaurants ? array_map(fn ($restaurantData) => new Restaurant($restaurantData), $queryRestaurants) : [];
    }

    public function getSortedRestaurants(string $searchQuery, string $sortColumn = 'id', string $sortDirection = 'asc'): array
    {
        $allowedColumns = ['restaurants.id', 'restaurants.restaurant_type', 'locations.name', 'locations.address'];
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'restaurants.id';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        $sql = '
            SELECT
                restaurants.id AS restaurant_id,
                restaurants.restaurant_type,
                restaurants.rating,
                restaurants.menu,
                locations.id AS location_id,
                locations.name AS location_name,
                locations.address AS location_address
            FROM restaurants
            LEFT JOIN locations ON restaurants.location_id = locations.id
        ';

        if (!empty($searchQuery)) {
            $sql .= " WHERE CONCAT(
                restaurants.restaurant_type, ' ',
                locations.name, ' ',
                locations.address
            ) LIKE :searchQuery";
        }

        $sql .= " ORDER BY {$sortColumn} {$sortDirection}";

        $query = $this->getConnection()->prepare($sql);
        if (!empty($searchQuery)) {
            $query->execute(['searchQuery' => "%{$searchQuery}%"]);
        } else {
            $query->execute();
        }
        $queryRestaurants = $query->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(
            function ($data) {
                $asset = $this->assetRepository->getAssetsByClass(Restaurant::class, $data['restaurant_id'], 'cover');
                $logo = $asset ? $asset[0]->getUrl() : null;

                return (object) [
                    'id' => $data['restaurant_id'],
                    'restaurant_type' => $data['restaurant_type'],
                    'rating' => $data['rating'],
                    'menu' => $data['menu'],
                    'location' => (object) [
                        'id' => $data['location_id'],
                        'name' => $data['location_name'],
                        'address' => $data['location_address'],
                    ],
                    'logo' => $logo,
                ];
            },
            $queryRestaurants
        );
    }

    public function getAllRestaurantsWithLocations(): array
    {
        $sql = '
            SELECT
                restaurants.id AS restaurant_id,
                restaurants.restaurant_type,
                restaurants.rating,
                restaurants.menu,
                locations.id AS location_id,
                locations.name AS location_name,
                locations.address AS location_address
            FROM restaurants
            LEFT JOIN locations ON restaurants.location_id = locations.id
        ';

        $query = $this->getConnection()->prepare($sql);
        $query->execute();
        $queryRestaurants = $query->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(
            function ($data) {
                return (object) [
                    'id' => $data['restaurant_id'],
                    'restaurant_type' => $data['restaurant_type'],
                    'rating' => $data['rating'],
                    'menu' => $data['menu'],
                    'location' => (object) [
                        'id' => $data['location_id'],
                        'name' => $data['location_name'],
                        'address' => $data['location_address'],
                    ],
                ];
            },
            $queryRestaurants
        );
    }

    public function deleteRestaurant(int $id): ?Restaurant
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryRestaurant = $this->getRestaurantById($id);

        if ($queryRestaurant) {
            $queryBuilder->table('restaurants')->where('id', '=', $id)->delete();

            return $queryRestaurant;
        }

        return null;
    }

    public function updateRestaurant(Restaurant $restaurant): void
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table('restaurants')->where('id', '=', $restaurant->id)->update(
            [
                'location_id' => $restaurant->location_id,
                'restaurant_type' => $restaurant->restaurant_type,
                'rating' => $restaurant->rating,
                'menu' => $restaurant->menu,
            ]
        );
    }

    public function getRestaurantWithLocationById(int $id): ?Restaurant
    {
        $query = $this->getConnection()->prepare("
            SELECT 
                r.*, 
                l.name, 
                l.address, 
                l.coordinates,
                l.event_type, 
                l.preview_description, 
                l.main_description 
            FROM restaurants r
            INNER JOIN locations l ON r.location_id = l.id
            WHERE r.id = :id
        ");

        $query->bindParam(':id', $id, type: \PDO::PARAM_INT);
        $query->execute();
        $restaurantWithLocation = $query->fetch(\PDO::FETCH_ASSOC);

        $restaurant = new Restaurant($restaurantWithLocation);
        $location = new Location($restaurantWithLocation);
        $restaurant->location = $location;

        return $restaurant ?: null;
    }                                           
}
