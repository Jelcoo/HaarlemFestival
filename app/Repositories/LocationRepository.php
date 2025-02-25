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
}
