<?php

namespace App\Repositories;

use App\Models\Artist;
use App\Helpers\QueryBuilder;
use App\Services\AssetService;

class ArtistRepository extends Repository
{
    private AssetService $assetService;

    public function __construct()
    {
        parent::__construct();

        $this->assetService = new AssetService();
    }

    public function getAllArtists(): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryArtists = $queryBuilder->table('artists')->get();

        return $this->mapArtists($queryArtists);
    }

    private function mapArtists(array $artists): array
    {
        return array_map(function ($artist) {
            $artist = new Artist($artist);
            $artist->assets = $this->assetService->resolveAssets($artist, 'cover');

            return $artist;
        }, $artists);
    }
}
