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

    public function getScheduleByArtistId(int $artistId): array
    {
        $query = $this->getConnection()->prepare("
            SELECT
                de.id AS event_id,
                de.start_date,
                de.start_time,
                de.end_date,
                de.end_time,
                l.name AS location_name,
                de.session,
                TIMESTAMPDIFF(MINUTE, CONCAT(de.start_date, ' ', de.start_time), CONCAT(de.end_date, ' ', de.end_time)) AS duration,
                de.total_tickets - COALESCE(COUNT(DISTINCT dt.id), 0) AS tickets_available,
                ROUND(de.price * (de.vat + 1), 2) AS price,
                GROUP_CONCAT(DISTINCT a.name ORDER BY a.name SEPARATOR ', ') AS artist_names
            FROM dance_events de
            JOIN locations l ON de.location_id = l.id
            JOIN dance_event_artists dea_filter ON de.id = dea_filter.event_id
            JOIN dance_event_artists dea ON de.id = dea.event_id
            JOIN artists a ON a.id = dea.artist_id
            LEFT JOIN dance_tickets dt ON de.id = dt.dance_event_id
            WHERE dea_filter.artist_id = :artistId
            GROUP BY de.id, de.start_date, de.start_time, de.end_date, de.end_time, l.name, de.session, de.total_tickets, de.price, de.vat
            ORDER BY de.start_date, de.start_time
        ");

        $query->bindParam(':artistId', $artistId, \PDO::PARAM_INT);
        $query->execute();
        $results = $query->fetchAll(\PDO::FETCH_ASSOC);

        // Add formatted date/time string
        foreach ($results as &$event) {
            $start = new \DateTime("{$event['start_date']} {$event['start_time']}");
            $event['starting_time_formatted'] = $start->format('j F H:i'); // e.g., 25 July 22:00
        }

        return $results;
    }
}
