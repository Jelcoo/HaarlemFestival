<?php

namespace App\Repositories;

use App\Models\EventDance;
use App\Helpers\QueryBuilder;

class DanceRepository extends Repository
{
    public function getEventById(int $id): ?EventDance
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryEvent = $queryBuilder->table('dance_events')->where('id', '=', $id)->first();

        return $queryEvent ? new EventDance($queryEvent) : null;
    }

    public function getSchedule(): array
    {
        $query = $this->getConnection()->prepare("
SELECT
    de.id AS event_id,
    de.start_date AS start_date,
    de.start_time AS start_time,
    l.name AS location_name,
    GROUP_CONCAT(DISTINCT a.name ORDER BY a.name SEPARATOR ', ') AS artist_names,
    de.session AS session,
    TIMESTAMPDIFF(MINUTE, CONCAT(de.start_date, ' ', de.start_time), CONCAT(de.end_date, ' ', de.end_time)) AS duration,
    de.total_tickets - COALESCE(COUNT(DISTINCT dt.id), 0) AS tickets_available,
    ROUND(de.price * (de.vat + 1), 2) AS price
FROM dance_events de
JOIN locations l ON de.location_id = l.id
JOIN dance_event_artists dea ON de.id = dea.event_id
JOIN artists a ON dea.artist_id = a.id
LEFT JOIN dance_tickets dt ON de.id = dt.dance_event_id
GROUP BY de.id, de.start_date, de.start_time, l.name, de.session, de.end_date, de.end_time, de.total_tickets, de.price, de.vat");

        $query->execute();
        $queryEvents = $query->fetchAll();

        return $queryEvents;
    }

    public function getScheduleFromArtistId(int $artistId): array
    {
        $query = $this->getConnection()->prepare("
SELECT
    de.id AS event_id,
    de.start_date AS start_date,
    de.start_time AS start_time,
    l.name AS location_name,
    GROUP_CONCAT(DISTINCT a.name ORDER BY a.name SEPARATOR ', ') AS artist_names,
    de.session AS session,
    TIMESTAMPDIFF(MINUTE, CONCAT(de.start_date, ' ', de.start_time), CONCAT(de.end_date, ' ', de.end_time)) AS duration,
    de.total_tickets - COALESCE(COUNT(DISTINCT dt.id), 0) AS tickets_available,
    ROUND(de.price * (de.vat + 1), 2) AS price
FROM dance_events de
JOIN locations l ON de.location_id = l.id
JOIN dance_event_artists dea ON de.id = dea.event_id
JOIN artists a ON dea.artist_id = a.id
LEFT JOIN dance_tickets dt ON de.id = dt.dance_event_id
WHERE dea.artist_id = :artistId
GROUP BY de.id, de.start_date, de.start_time, l.name, de.session, de.end_date, de.end_time, de.total_tickets, de.price, de.vat");

        $query->execute([
            'artistId' => $artistId,
        ]);
        $queryEvents = $query->fetchAll();

        return $queryEvents;
    }

    public function getSortedEvents(string $searchQuery, string $sortColumn = 'event_id', string $sortDirection = 'asc'): array
    {
        $allowedColumns = [
            'event_id',
            'start_date',
            'start_time',
            'end_date',
            'end_time',
            'location_name',
            'artist_names',
            'session',
            'duration',
            'tickets_available',
            'price',
            'vat',
        ];

        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'event_id';
        }

        if (!in_array(strtolower($sortDirection), ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        $query = $this->getConnection()->prepare("
            SELECT
                de.id AS event_id,
                de.start_date,
                de.start_time,
                de.end_date,
                de.end_time,
                de.vat,
                l.name AS location_name,
                GROUP_CONCAT(DISTINCT a.name ORDER BY a.name SEPARATOR ', ') AS artist_names,
                de.session,
                TIMESTAMPDIFF(MINUTE, CONCAT(de.start_date, ' ', de.start_time), CONCAT(de.end_date, ' ', de.end_time)) AS duration,
                de.total_tickets - COALESCE(COUNT(DISTINCT dt.id), 0) AS tickets_available,
                ROUND(de.price * (de.vat + 1), 2) AS price
            FROM dance_events de
            JOIN locations l ON de.location_id = l.id
            LEFT JOIN dance_event_artists dea ON de.id = dea.event_id
            LEFT JOIN artists a ON dea.artist_id = a.id
            LEFT JOIN dance_tickets dt ON de.id = dt.dance_event_id
            WHERE (
                l.name LIKE :search OR 
                a.name LIKE :search2 OR 
                de.session LIKE :search3 OR 
                de.total_tickets LIKE :search4 OR 
                de.price LIKE :search5 OR 
                de.vat LIKE :search6 OR 
                de.start_date LIKE :search7 OR 
                de.end_date LIKE :search8 OR 
                de.start_time LIKE :search9 OR 
                de.end_time LIKE :search10
            )
            GROUP BY de.id
            ORDER BY {$sortColumn} {$sortDirection}
        ");

        $params = array_fill_keys([
            'search',
            'search2',
            'search3',
            'search4',
            'search5',
            'search6',
            'search7',
            'search8',
            'search9',
            'search10',
        ], '%' . $searchQuery . '%');

        $query->execute($params);

        return $query->fetchAll();
    }

    public function createEvent(array $data): EventDance
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $eventId = $queryBuilder->table('dance_events')->insert([
            'location_id' => $data['location_id'],
            'start_date' => $data['start_date'],
            'start_time' => $data['start_time'],
            'end_date' => $data['end_date'],
            'end_time' => $data['end_time'],
            'session' => $data['session'],
            'total_tickets' => $data['total_tickets'],
            'price' => $data['price'],
            'vat' => $data['vat'],
        ]);

        $event = $this->getEventById((int) $eventId);

        return $event;
    }

    public function deleteEvent(int $id): ?EventDance
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryEvent = $this->getEventById($id);

        if ($queryEvent) {
            $queryBuilder->table('dance_events')->where('id', '=', $id)->delete();

            return $queryEvent;
        }

        return null;
    }

    public function updateEvent(EventDance $event): void
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table('dance_events')->where('id', '=', $event->id)->update(
            [
                'location_id' => $event->location_id,
                'start_date' => $event->start_date,
                'start_time' => $event->start_time,
                'end_date' => $event->end_date,
                'end_time' => $event->end_time,
                'session' => $event->session->value,
                'total_tickets' => $event->total_tickets,
                'price' => $event->price,
                'vat' => $event->vat,
            ]
        );
    }

    public function getEventArtists(int $eventId): array
    {
        $query = $this->getConnection()->prepare('
            SELECT a.id, a.name 
            FROM dance_event_artists dea 
            JOIN artists a ON dea.artist_id = a.id 
            WHERE dea.event_id = :eventId
        ');

        $query->execute(['eventId' => $eventId]);

        return $query->fetchAll();
    }

    public function attachArtistsToEvent(int $eventId, array $artistIds): void
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        foreach ($artistIds as $artistId) {
            $queryBuilder->table('dance_event_artists')->insert([
                'event_id' => $eventId,
                'artist_id' => $artistId,
            ]);
        }
    }

    public function detachArtistsFromEvent(int $eventId): void
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table('dance_event_artists')->where('event_id', '=', $eventId)->delete();
    }

    public function eventHasTickets(int $eventId): bool
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        return (bool) $queryBuilder->table('dance_tickets')->where('dance_event_id', '=', $eventId)->first();
    }
}
