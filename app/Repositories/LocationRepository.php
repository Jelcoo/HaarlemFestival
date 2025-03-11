<?php

namespace App\Repositories;

use App\Models\Location;
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

    public function getSpecificLocations(string $type): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryLocations = $queryBuilder->table('locations')->where('event_type', '=', $type)->get();

        return $this->mapLocations($queryLocations);
    }

    private function mapLocations(array $locations): array
    {
        return array_map(function ($location) {
            $location = new Location($location);
            $location->assets = $this->assetService->resolveAssets($location, 'cover');

            return $location;
        }, $locations);
    }
}
