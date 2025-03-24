<?php

namespace App\Repositories;

use App\Models\Location;
use App\Models\Restaurant;
use App\Helpers\QueryBuilder;

class RestaurantRepository extends Repository
{
    private AssetRepository $assetRepository;
    private LocationRepository $locationRepository;

    public function __construct()
    {
        parent::__construct();

        $this->assetRepository = new AssetRepository();
        $this->locationRepository = new LocationRepository();
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

    public function getRestaurantByIdWithLocation(int $id): ?Restaurant
    {
        $restaurant = $this->getRestaurantById($id);

        if ($restaurant) {
            $restaurant->location = $this->locationRepository->getLocationById($restaurant->location_id);
        }

        return $restaurant;
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

    public function getEventsByRestaurantId(int $restaurantId): array
    {
        $sql = "
            SELECT 
                ye.id AS event_id,
                ye.start_date,
                ye.start_time,
                ye.end_date,
                ye.end_time,
                ye.total_seats,
                ye.kids_price,
                ye.adult_price,
                ye.reservation_cost,
                l.name AS location_name,
                l.address AS location_address,
                TIMESTAMPDIFF(MINUTE, 
                    CONCAT(ye.start_date, ' ', ye.start_time), 
                    CONCAT(ye.end_date, ' ', ye.end_time)
                ) AS duration,
                COALESCE(SUM(yt.kids_count + yt.adult_count), 0) AS reserved
            FROM yummy_events ye
            INNER JOIN restaurants r ON r.id = ye.restaurant_id
            INNER JOIN locations l ON l.id = r.location_id
            LEFT JOIN yummy_tickets yt ON yt.yummy_event_id = ye.id
            WHERE r.id = :restaurantId
            GROUP BY ye.id, ye.start_date, ye.start_time, ye.end_date, ye.end_time, l.name, l.address
            ORDER BY ye.start_date, ye.start_time
        ";

        $query = $this->getConnection()->prepare($sql);
        $query->execute(['restaurantId' => $restaurantId]);
        $results = $query->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(function ($row) {
            return [
                'event_id' => (int)$row['event_id'],
                'start_datetime' => date('Y-m-d H:i', strtotime($row['start_date'] . ' ' . $row['start_time'])),
                'duration' => (int)$row['duration'],
                'location_name' => $row['location_name'],
                'location_address' => $row['location_address'],
                'tickets_available' => max(0, $row['total_seats'] - $row['reserved']),
                'kids_price' => (float)$row['kids_price'],
                'adult_price' => (float)$row['adult_price'],
                'reservation_cost' => (float)$row['reservation_cost'],
            ];
        }, $results);
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
    

}
