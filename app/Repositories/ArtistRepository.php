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

    public function createArtist(array $data): Artist
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $artistId = $queryBuilder->table('artists')->insert($data);
        $artist = $this->getArtistById((int) $artistId);

        return $artist;
    }

    public function getArtistById(int $id): ?Artist
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryArtist = $queryBuilder->table('artists')->where('id', '=', $id)->first();

        return $queryArtist ? new Artist($queryArtist) : null;
    }

    public function getArtistsByEventId(int $eventId): array
    {
        $query = $this->getConnection()->prepare('
SELECT
	a.id,
    a.name,
    a.preview_description,
    a.main_description,
    a.iconic_albums
FROM dance_events de
JOIN dance_event_artists dea ON de.id = dea.event_id
JOIN artists a ON dea.artist_id = a.id
WHERE de.id = :eventId');

        $query->execute([
            'eventId' => $eventId,
        ]);
        $queryArtists = $query->fetchAll();

        return $this->mapArtists($queryArtists);
    }

    public function getAllArtists(): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryArtists = $queryBuilder->table('artists')->get();

        return $this->mapArtists($queryArtists);
    }

    public function getSortedArtists(string $searchQuery, string $sortColumn = 'id', string $sortDirection = 'asc'): array
    {
        $allowedColumns = ['id', 'name', 'preview_description', 'main_description', 'iconic_albums'];
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        $queryBuilder = new QueryBuilder($this->getConnection());
        $query = $queryBuilder->table('artists');

        if (!empty($searchQuery)) {
            $query->where('name', 'LIKE', "%{$searchQuery}%")
                ->orWhere('preview_description', 'LIKE', "%{$searchQuery}%")
                ->orWhere('main_description', 'LIKE', "%{$searchQuery}%")
                ->orWhere('iconic_albums', 'LIKE', "%{$searchQuery}%");
        }

        $queryArtists = $query->orderBy($sortColumn, $sortDirection)->get();

        return $this->mapArtists($queryArtists);
    }

    public function deleteArtist(int $id): ?Artist
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryArtist = $this->getArtistById($id);

        if ($queryArtist) {
            $queryBuilder->table('artists')->where('id', '=', $id)->delete();

            return $queryArtist;
        }

        return null;
    }

    public function updateArtist(Artist $artist): void
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table('artists')->where('id', '=', $artist->id)->update(
            [
                'name' => $artist->name,
                'preview_description' => $artist->preview_description,
                'main_description' => $artist->main_description,
                'iconic_albums' => $artist->iconic_albums,
            ]
        );
    }

    private function mapArtists(array $artists): array
    {
        return array_map(
            function ($artist) {
                $artist = new Artist($artist);
                $artist->assets = $this->assetService->resolveAssets($artist, 'cover');

                return $artist;
            },
            $artists
        );
    }
}
