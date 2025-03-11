<?php

namespace App\Repositories;

class DanceRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSchedule(): array
    {
        $query = $this->getConnection()->prepare("
SELECT
    de.start_date AS start_date,
    de.start_time AS start_time,
    l.name AS location_name,
    GROUP_CONCAT(a.name ORDER BY a.name SEPARATOR ', ') AS artist_names,
    de.session AS session,
    TIMESTAMPDIFF(MINUTE, CONCAT(de.start_date, ' ', de.start_time), CONCAT(de.end_date, ' ', de.end_time)) AS duration,
    de.total_tickets - COALESCE(COUNT(dt.id), 0) AS tickets_available,
    ROUND(de.price * (de.vat + 1)) AS price
FROM dance_events de
JOIN locations l ON de.location_id = l.id
JOIN dance_event_artists dea ON de.id = dea.event_id
JOIN artists a ON dea.artist_id = a.id
LEFT JOIN dance_tickets dt ON de.id = dt.dance_event_id
GROUP BY de.id, de.start_date, de.start_time, l.name, de.session, de.end_date, de.end_time, de.total_tickets, de.price, de.vat;");

        $query->execute();
        $queryEvents = $query->fetchAll();

        return $queryEvents;
    }
}
