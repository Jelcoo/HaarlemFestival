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

    public function getAllEvents(): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        $events = $queryBuilder->table('yummy_events')->get();

        return $events ? array_map(fn ($eventData) => new EventYummy($eventData), $events) : [];
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
