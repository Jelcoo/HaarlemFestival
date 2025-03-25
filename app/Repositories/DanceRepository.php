<?php

namespace App\Repositories;

use App\Models\EventDance;
use App\Helpers\QueryBuilder;

class DanceRepository extends Repository
{
    public function getEventById(int $id): EventDance
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

    public function getAllEvents(): array
    {
        $query = $this->getConnection()->prepare("
SELECT
    de.id AS event_id,
    de.start_date AS start_date,
    de.start_time AS start_time,
    de.end_date AS end_date,
    de.end_time AS end_time,
    de.vat AS vat,
    l.name AS location_name,
    GROUP_CONCAT(DISTINCT a.name ORDER BY a.name SEPARATOR ', ') AS artist_names,
    de.session AS session,
    TIMESTAMPDIFF(MINUTE, CONCAT(de.start_date, ' ', de.start_time), CONCAT(de.end_date, ' ', de.end_time)) AS duration,
    de.total_tickets - COALESCE(COUNT(DISTINCT dt.id), 0) AS tickets_available,
    ROUND(de.price * (de.vat + 1), 2) AS price
FROM dance_events de
JOIN locations l ON de.location_id = l.id
LEFT JOIN dance_event_artists dea ON de.id = dea.event_id
LEFT JOIN artists a ON dea.artist_id = a.id
LEFT JOIN dance_tickets dt ON de.id = dt.dance_event_id
GROUP BY de.id, de.start_date, de.start_time, l.name, de.session, de.end_date, de.end_time, de.total_tickets, de.price, de.vat");

        $query->execute();
        $queryEvents = $query->fetchAll();

        return $queryEvents;
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
        $query = $this->getConnection()->prepare("
            SELECT a.id, a.name 
            FROM dance_event_artists dea 
            JOIN artists a ON dea.artist_id = a.id 
            WHERE dea.event_id = :eventId
        ");

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
}
