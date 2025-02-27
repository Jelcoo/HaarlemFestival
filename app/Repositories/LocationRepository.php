<?php

namespace App\Repositories;

use App\Helpers\QueryBuilder;
use App\Models\Location;

class LocationRepository extends Repository
{
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

        return $queryLocations ? array_map(fn($locationData) => new Location($locationData), $queryLocations) : [];
    }

    public function getSortedLocations(string $searchQuery, string $sortColumn = 'id', string $sortDirection = 'asc'): array
    {
        $allowedColumns = ['id', 'name', 'address'];
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
                ->orWhere('address', 'LIKE', "%{$searchQuery}%");
        }

        $queryLocations = $query->orderBy($sortColumn, $sortDirection)->get();

        return $queryLocations ? array_map(fn($locationData) => new Location($locationData), $queryLocations) : [];
    }

}
