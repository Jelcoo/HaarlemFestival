<?php

namespace App\Repositories;

use App\Models\Location;
use App\Helpers\QueryBuilder;

class LocationRepository extends Repository
{
    public function getAllLocations(): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryLocations = $queryBuilder->table('locations')->get();

        return array_map(function ($location) {
            return new Location($location);
        }, $queryLocations);
    }
}
