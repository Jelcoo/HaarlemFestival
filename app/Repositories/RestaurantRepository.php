<?php

namespace App\Repositories;

use App\Helpers\QueryBuilder;
use App\Models\Restaurant;

class RestaurantRepository extends Repository
{
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

        return $queryRestaurants ? array_map(fn($restaurantData) => new Restaurant($restaurantData), $queryRestaurants) : [];
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

        $sql = "
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
        ";

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

        return array_map(function ($data) {
            return (object) [
                'id' => $data['restaurant_id'],
                'restaurant_type' => $data['restaurant_type'],
                'rating' => $data['rating'],
                'menu' => $data['menu'],
                'location' => (object) [
                    'id' => $data['location_id'],
                    'name' => $data['location_name'],
                    'address' => $data['location_address']
                ]
            ];
        }, $queryRestaurants);
    }

    public function getAllRestaurantsWithLocations(): array
    {
        $sql = "
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
        ";

        $query = $this->getConnection()->prepare($sql);
        $query->execute();
        $queryRestaurants = $query->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(function ($data) {
            return (object) [
                'id' => $data['restaurant_id'],
                'restaurant_type' => $data['restaurant_type'],
                'rating' => $data['rating'],
                'menu' => $data['menu'],
                'location' => (object) [
                    'id' => $data['location_id'],
                    'name' => $data['location_name'],
                    'address' => $data['location_address']
                ]
            ];
        }, $queryRestaurants);
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
        $queryBuilder->table('restaurants')->where('id', '=', $restaurant->id)->update([
            'location_id' => $restaurant->location_id,
            'restaurant_type' => $restaurant->restaurant_type,
            'rating' => $restaurant->rating,
            'menu' => $restaurant->menu,
        ]);
    }
}
