<?php

namespace App\Repositories;

use App\Models\Location;
use App\Models\Restaurant;
use App\Helpers\QueryBuilder;
use App\Services\AssetService;

class LocationRepository extends Repository
{
    private AssetService $assetService;

    public function __construct()
    {
        parent::__construct();

        $this->assetService = new AssetService();
    }

    public function createLocation(array $data): Location
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $locationId = $queryBuilder->table('locations')->insert($data);
        $location = $this->getLocationById((int) $locationId);

        return $location;
    }

    public function getLocationById(int $id): ?Location
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryLocation = $queryBuilder->table('locations')->where('id', '=', $id)->first();

        return $queryLocation ? new Location($queryLocation) : null;
    }

    public function getAllLocations(): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryLocations = $queryBuilder->table('locations')->get();

        return $this->mapLocations($queryLocations);
    }

    public function getSortedLocations(string $searchQuery, string $sortColumn = 'id', string $sortDirection = 'asc'): array
    {
        $allowedColumns = ['id', 'name', 'address', 'event_type'];
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        $queryBuilder = new QueryBuilder($this->getConnection());
        $query = $queryBuilder->table('locations');

        if (!empty($searchQuery)) {
            $query->where('name', 'LIKE', "%{$searchQuery}%")
                ->orWhere('address', 'LIKE', "%{$searchQuery}%")
                ->orWhere('event_type', 'LIKE', "%{$searchQuery}%");
        }

        $queryLocations = $query->orderBy($sortColumn, $sortDirection)->get();

        return $this->mapLocations($queryLocations);
    }

    public function deleteLocation(int $id): ?Location
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryLocation = $this->getLocationById($id);

        if ($queryLocation) {
            $queryBuilder->table('locations')->where('id', '=', $id)->delete();

            return $queryLocation;
        }

        return null;
    }

    public function updateLocation(Location $location): void
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table('locations')->where('id', '=', $location->id)->update(
            [
                'name' => $location->name,
                'event_type' => $location->event_type->value,
                'coordinates' => $location->coordinates,
                'address' => $location->address,
                'preview_description' => $location->preview_description,
                'main_description' => $location->main_description,
            ]
        );
    }

    public function getHomeLocations(): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryLocations = $queryBuilder->table('locations')
            ->where('event_type', '=', 'yummy')
            ->orWhere('event_type', '=', 'dance')
            ->orWhere('event_type', '=', 'teylers')
            ->get();

        return $this->mapLocations($queryLocations);
    }

    public function getYummyLocations(): array
    {
        $query = $this->getConnection()->prepare('
SELECT 
    r.id AS id,
    l.id AS location_id,
    r.restaurant_type,
    r.rating,
    r.menu,
    l.id AS location_id,
    l.name AS name,
    l.event_type,
    l.coordinates,
    l.address,
    l.preview_description,
    l.main_description
FROM restaurants r
INNER JOIN locations l ON r.location_id = l.id');

        $query->execute();
        $queryRestaurants = $query->fetchAll();

        return array_map(function ($restaurant) {
            $restaurantModel = new Restaurant($restaurant);
            $restaurantModel->location = new Location($restaurant);
            $restaurantModel->location->id = $restaurant['location_id'];
            $restaurantModel->assets = array_merge(
                $restaurantModel->assets,
                $this->assetService->resolveAssets($restaurantModel, 'cover'),
                $this->assetService->resolveAssets($restaurantModel, 'icon')
            );

            return $restaurantModel;
        }, $queryRestaurants);
    }

    public function getSpecificLocations(string $type): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryLocations = $queryBuilder->table('locations')->where('event_type', '=', $type)->get();

        return $this->mapLocations($queryLocations);
    }

    private function mapLocations(array $locations): array
    {
        return array_map(
            function ($location) {
                $location = new Location($location);
                $location->assets = $this->assetService->resolveAssets($location, 'cover');

                return $location;
            },
            $locations
        );
    }
}
