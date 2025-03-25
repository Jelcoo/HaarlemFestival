<?php

namespace App\Repositories;

use App\Models\EventHistory;
use App\Helpers\QueryBuilder;

class HistoryRepository extends Repository
{
    public function getEventById(int $id): EventHistory
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

    public function getAllEvents(): array
    {
        $query = $this->getConnection()->prepare('
SELECT
    he.id AS id,
    he.start_location AS start_location,
    he.seats_per_tour AS seats_per_tour,
    he.family_price AS family_price,
    he.single_price AS single_price,
    he.vat AS vat,
    he.language AS language,
    he.guide AS guide,
    he.start_date AS start_date,
    he.start_time AS start_time,
    he.end_date AS end_date,
    he.end_time AS end_time
FROM history_events he');

        $query->execute();
        $queryEvents = $query->fetchAll();

        return $queryEvents;
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
}
