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
        $allowedColumns = ['id', 'restaurant_type', 'rating'];
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        $queryBuilder = new QueryBuilder($this->getConnection());
        $query = $queryBuilder->table('restaurants');

        if (!empty($searchQuery)) {
            $query->where('name', 'LIKE', "%{$searchQuery}%")
                ->orWhere('restaurant_type', 'LIKE', "%{$searchQuery}%")
                ->orWhere('address', 'LIKE', "%{$searchQuery}%");
        }

        $queryRestaurants = $query->orderBy($sortColumn, $sortDirection)->get();

        return $queryRestaurants ? array_map(fn($restaurantData) => new Restaurant($restaurantData), $queryRestaurants) : [];
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

    // public function updateRestaurant(Restaurant $restaurant): ?Restaurant {
    //     $queryBuilder = new QueryBuilder($this->getConnection());

    //     $existingRestaurant = $this->getRestaurantById($restaurant->id);
    //     if (!$existingRestaurant) {
    //         return null;
    //     }

    //     $fieldsToCompare = [
    //         'name' => $restaurant->name,
    //         'restaurant_type' => $restaurant->restaurant_type,
    //         'rating' => $restaurant->rating,
    //         'location_id' => $restaurant->location_id,
    //         'menu' => $restaurant->menu,
    //     ];

    //     $updatedFields = [];

    //     foreach ($fieldsToCompare as $field => $newValue) {
    //         if ($newValue !== $existingRestaurant->$field) {
    //             $updatedFields[$field] = $newValue;
    //         }
    //     }

    //     if (!empty($updatedFields)) {
    //         $queryBuilder->table('restaurants')->where('id', '=', $restaurant->id)->update($updatedFields);

    //         return $this->getRestaurantById($restaurant->id);
    //     }

    //     return $existingRestaurant;
    // }
}
