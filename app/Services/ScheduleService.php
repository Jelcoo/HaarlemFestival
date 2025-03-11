<?php

namespace App\Services;

use App\Enum\DanceSessionEnum;
use App\Repositories\DanceRepository;

class ScheduleService
{
    private DanceRepository $danceRepository;

    public function __construct()
    {
        $this->danceRepository = new DanceRepository();
    }

    public function getDanceSchedule(): array
    {
        $querySchedule = $this->danceRepository->getSchedule();
        $schedules = [];

        $dates = $this->getScheduleDates($querySchedule);
        foreach ($dates as $date) {
            $todayEvents = $this->getScheduleByDate($querySchedule, $date);
            $formattedDate = date('l F j', strtotime($date));
            $todaySchedule = [
                'date' => $formattedDate,
                'rows' => [],
            ];

            foreach ($todayEvents as $event) {
                $todaySchedule['rows'][] = [
                    'event_id' => $event['event_id'],
                    'start' => $event['start_time'],
                    'venue' => $event['location_name'],
                    'artists' => explode(', ', $event['artist_names']),
                    'session' => match (DanceSessionEnum::from($event['session'])) {
                        DanceSessionEnum::CLUB => 'Club',
                        DanceSessionEnum::B2B => 'Back2Back',
                        DanceSessionEnum::TIESTO_WORLD => 'Tiesto World',
                    },
                    'duration' => $event['duration'],
                    'tickets_available' => $event['tickets_available'],
                    'price' => $event['price'],
                ];
            }

            $schedules[] = $todaySchedule;
        }

        return $schedules;
    }

    private function getScheduleDates(array $schedule): array
    {
        $dates = array_map(function ($event) {
            return $event['start_date'];
        }, $schedule);

        return array_unique($dates);
    }

    private function getScheduleByDate(array $schedule, string $date): array
    {
        return array_filter($schedule, function ($event) use ($date) {
            if (!isset($event['start_date'])) {
                return false;
            }

            return $event['start_date'] === $date;
        });
    }
}
