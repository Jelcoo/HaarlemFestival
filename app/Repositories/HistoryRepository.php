<?php

namespace App\Repositories;

class HistoryRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
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
}
