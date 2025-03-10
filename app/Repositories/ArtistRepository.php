<?php

namespace App\Repositories;

use App\Helpers\QueryBuilder;
use App\Models\Artist;

class ArtistRepository extends Repository
{
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

    public function getAllArtists(): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryArtists = $queryBuilder->table('artists')->get();

        return $queryArtists ? array_map(fn($artistData) => new Artist($artistData), $queryArtists) : [];
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

        return $queryArtists ? array_map(fn($artistData) => new Artist($artistData), $queryArtists) : [];
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

    public function updateArtist(Artist $artist): ?Artist
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $existingArtist = $this->getArtistById($artist->id);
        if (!$existingArtist) {
            return null;
        }

        $fieldsToCompare = [
            'name' => $artist->name,
            'preview_description' => $artist->preview_description,
            'main_description' => $artist->main_description,
            'iconic_albums' => $artist->iconic_albums,
        ];

        $updatedFields = [];

        foreach ($fieldsToCompare as $field => $newValue) {
            if ($newValue !== $existingArtist->$field) {
                $updatedFields[$field] = $newValue;
            }
        }

        if (!empty($updatedFields)) {
            $queryBuilder->table('artists')->where('id', '=', $artist->id)->update($updatedFields);

            return $this->getArtistById($artist->id);
        }

        return $existingArtist;
    }
}
