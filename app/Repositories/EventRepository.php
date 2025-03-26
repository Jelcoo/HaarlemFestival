<?php

namespace App\Repositories;
use App\Helpers\QueryBuilder;

class EventRepository extends Repository
{
    public function getAllDanceInformation(): array
    {
        $danceInfo = [];
    
        foreach ($this->getAllDanceIds() as $id) {
            $sessionInfo = $this->getDanceSessionById($id);
            if ($sessionInfo) {
                $danceInfo[] = ['id' => $id, 'info' => $sessionInfo];
            }
        }
    
        return $danceInfo;
    }
    private function getAllDanceIds(): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $danceEvents = $queryBuilder
            ->table('dance_events')
            ->select(['id'])
            ->get();

        return array_column($danceEvents, 'id');
    }
    private function getDanceSessionById(int $id): string
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $danceEvent = $queryBuilder
            ->table('dance_events')
            ->select(['session', 'start_date', 'start_time'])
            ->where('id', '=', $id)
            ->first();

        return $danceEvent ? "{$danceEvent['session']} - {$danceEvent['start_date']} at {$danceEvent['start_time']}" : '';
    }

    public function getAllHistoryEventInformation(): array
    {
        $historyInfo = [];

        foreach ($this->getAllHistoryEventIds() as $id) {
            $scheduleInfo = $this->getGuideScheduleById($id);
            if ($scheduleInfo) {
                $historyInfo[] = ['id' => $id, 'info' => $scheduleInfo];
            }
        }

        return $historyInfo;
    }
    private function getAllHistoryEventIds(): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
    
        $historyEvents = $queryBuilder
            ->table('history_events')
            ->select(['id'])
            ->get();
    
        return array_column($historyEvents, 'id');
    }
    private function getGuideScheduleById(int $id): string
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $eventDetail = $queryBuilder
            ->table('history_events')
            ->select(['guide', 'start_date', 'start_time'])
            ->where('id', '=', $id)
            ->first();

        return $eventDetail ? "{$eventDetail['guide']} - {$eventDetail['start_date']} at {$eventDetail['start_time']}" : '';
    }
    public function getAllYummyEventInformation(): array
    {
        $yummyInfo = [];

        foreach ($this->getAllYummyEventIds() as $id) {
            $eventDetails = $this->getYummyEventDetailsById($id);
            if ($eventDetails) {
                $yummyInfo[] = ['id' => $id, 'info' => $eventDetails];
            }
        }

        return $yummyInfo;
    }
    private function getAllYummyEventIds(): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $yummyEvents = $queryBuilder
            ->table('yummy_events')
            ->select(['id'])
            ->get();

        return array_column($yummyEvents, 'id');
    }
    private function getYummyEventDetailsById(int $id): string
    {
        $query = $this->getConnection()->prepare('
            SELECT l.name, y.start_date, y.start_time
            FROM yummy_events y
            JOIN locations l ON y.restaurant_id = l.id
            WHERE y.id = :eventId;
        ');

        $query->bindValue(':eventId', $id, \PDO::PARAM_INT);
        $query->execute();
        $eventDetail = $query->fetch();

        return $eventDetail ? "{$eventDetail['name']} - {$eventDetail['start_date']} at {$eventDetail['start_time']}" : '';
    }
    


}