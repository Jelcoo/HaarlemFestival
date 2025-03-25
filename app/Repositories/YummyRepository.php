<?php

namespace App\Repositories;

use App\Models\EventYummy;
use App\Helpers\QueryBuilder;

class YummyRepository extends Repository
{
    public function getEventById(int $id): EventYummy
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryEvent = $queryBuilder->table('yummy_events')->where('id', '=', $id)->first();

        return $queryEvent ? new EventYummy($queryEvent) : null;
    }

    public function getSortedEvents(string $searchQuery, string $sortColumn = 'id', string $sortDirection = 'asc'): array
    {
        $allowedColumns = [
            'id', 'restaurant_name', 'total_seats', 'kids_price', 'adult_price',
            'reservation_cost', 'vat', 'start_time', 'start_date', 'end_time', 'end_date'
        ];

        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }

        if (!in_array(strtolower($sortDirection), ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        $query = $this->getConnection()->prepare("
            SELECT 
                ye.*, 
                l.name AS restaurant_name
            FROM yummy_events ye
            JOIN locations l ON ye.restaurant_id = l.id
            WHERE 
                l.name LIKE :search OR
                total_seats LIKE :search2 OR
                kids_price LIKE :search3 OR
                adult_price LIKE :search4 OR
                reservation_cost LIKE :search5 OR
                vat LIKE :search6 OR
                start_time LIKE :search7 OR
                start_date LIKE :search8 OR
                end_time LIKE :search9 OR
                end_date LIKE :search10
            ORDER BY {$sortColumn} {$sortDirection}
        ");

        $params = array_fill_keys([
            'search', 'search2', 'search3', 'search4', 'search5',
            'search6', 'search7', 'search8', 'search9', 'search10'
        ], '%' . $searchQuery . '%');

        $query->execute($params);
        $results = $query->fetchAll();

        return array_map(function ($eventData) {
            $event = new EventYummy($eventData);
            $event->restaurant_name = $eventData['restaurant_name'] ?? '-';
            return $event;
        }, $results);
    }

    public function createEvent(array $data): bool
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table('yummy_events')->insert($data);

        return true;
    }

    public function deleteEvent(int $id): bool
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table('yummy_events')->where('id', '=', $id)->delete();

        return true;
    }

    public function updateEvent(EventYummy $event): bool
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryBuilder->table('yummy_events')->where('id', '=', $event->id)->update([
            'restaurant_id' => $event->restaurant_id,
            'total_seats' => $event->total_seats,
            'kids_price' => $event->kids_price,
            'adult_price' => $event->adult_price,
            'reservation_cost' => $event->reservation_cost,
            'vat' => $event->vat,
            'start_time' => $event->start_time,
            'start_date' => $event->start_date,
            'end_time' => $event->end_time,
            'end_date' => $event->end_date,
        ]);

        return true;
    }
}
