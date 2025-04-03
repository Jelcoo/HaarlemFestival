<?php

namespace App\Repositories;

use App\Models\EventHistory;
use App\Helpers\QueryBuilder;

class HistoryRepository extends Repository
{
    public function getEventById(int $id): ?EventHistory
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryEvent = $queryBuilder->table('history_events')->where('id', '=', $id)->first();

        return $queryEvent ? new EventHistory($queryEvent) : null;
    }

    public function getSchedule(): array
    {
        $query = $this->getConnection()->prepare('
SELECT
    he.id AS tour_id,
    he.start_location AS start_location,
    he.seats_per_tour AS seats_per_tour,
    ROUND(he.family_price * (he.vat + 1), 2) AS family_price,
    ROUND(he.single_price * (he.vat + 1), 2) AS single_price,
    he.language AS language,
    he.guide AS guide,
    he.start_date AS start_date,
    he.start_time AS start_time
FROM history_events he');

        $query->execute();
        $queryEvents = $query->fetchAll();

        return $queryEvents;
    }

    public function getSortedEvents(string $searchQuery, string $sortColumn = 'id', string $sortDirection = 'asc'): array
    {
        $allowedColumns = [
            'id', 'language', 'guide', 'seats_per_tour',
            'family_price', 'single_price', 'vat',
            'start_location', 'start_time', 'start_date',
            'end_time', 'end_date',
        ];

        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }

        if (!in_array(strtolower($sortDirection), ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        $query = $this->getConnection()->prepare("
            SELECT
                he.id AS id,
                he.language,
                he.guide,
                he.seats_per_tour,
                he.family_price,
                he.single_price,
                he.vat,
                he.start_location,
                he.start_time,
                he.start_date,
                he.end_time,
                he.end_date
            FROM history_events he
            WHERE
                he.language LIKE :search OR
                he.guide LIKE :search2 OR
                he.seats_per_tour LIKE :search3 OR
                he.family_price LIKE :search4 OR
                he.single_price LIKE :search5 OR
                he.vat LIKE :search6 OR
                he.start_location LIKE :search7 OR
                he.start_time LIKE :search8 OR
                he.start_date LIKE :search9 OR
                he.end_time LIKE :search10 OR
                he.end_date LIKE :search11
            ORDER BY {$sortColumn} {$sortDirection}
        ");

        $params = array_fill_keys(
            ['search', 'search2', 'search3', 'search4', 'search5', 'search6', 'search7', 'search8', 'search9', 'search10', 'search11'],
            '%' . $searchQuery . '%'
        );

        $query->execute($params);

        return $query->fetchAll();
    }

    public function createEvent(array $data): bool
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table('history_events')->insert($data);

        return true;
    }

    public function deleteEvent(int $id): bool
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table('history_events')->where('id', '=', $id)->delete();

        return true;
    }

    public function updateEvent(EventHistory $event): bool
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryBuilder->table('history_events')->where('id', '=', $event->id)->update([
            'language' => $event->language,
            'guide' => $event->guide,
            'seats_per_tour' => $event->seats_per_tour,
            'family_price' => $event->family_price,
            'single_price' => $event->single_price,
            'vat' => $event->vat,
            'start_location' => $event->start_location,
            'start_time' => $event->start_time,
            'start_date' => $event->start_date,
            'end_time' => $event->end_time,
            'end_date' => $event->end_date,
        ]);

        return true;
    }

    public function eventHasTickets(int $eventId): bool
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        return (bool) $queryBuilder->table('history_tickets')->where('history_event_id', '=', $eventId)->first();
    }
}
