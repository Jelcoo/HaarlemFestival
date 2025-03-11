<?php

namespace App\Services;

use App\Enum\DanceSessionEnum;
use App\Repositories\DanceRepository;
use App\Repositories\HistoryRepository;

class ScheduleService
{
    private DanceRepository $danceRepository;
    private HistoryRepository $historyRepository;

    public function __construct()
    {
        $this->danceRepository = new DanceRepository();
        $this->historyRepository = new HistoryRepository();
    }

    public function getDanceSchedule(): array
    {
        $querySchedule = $this->danceRepository->getSchedule();
        $schedules = [];

        $dates = $this->getScheduleDates($querySchedule);
        foreach ($dates as $date) {
            $todayEvents = $this->getScheduleByDate($querySchedule, $date);
            $todaySchedule = [
                'date' => date('l F j', strtotime($date)),
                'rows' => [],
            ];

            foreach ($todayEvents as $event) {
                $todaySchedule['rows'][] = [
                    'event_id' => $event['event_id'],
                    'start' => date('H:i', strtotime($event['start_time'])),
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

    public function getHistorySchedule(): array
    {
        $querySchedule = $this->historyRepository->getSchedule();
        $schedules = [];

        $dates = $this->getScheduleDates($querySchedule);
        foreach ($dates as $date) {
            $todayEvents = $this->getScheduleByDate($querySchedule, $date);
            $uniqueTours = $this->getUniqueTours($todayEvents);

            foreach ($uniqueTours as $key => $tours) {
                $firstTour = $tours[0];
                $languageNames = array_values(array_unique(array_column($tours, 'language')));
                $guides = array_values(array_map(function ($language) use ($tours) {
                    $toursInLang = array_filter($tours, fn($tour) => $tour['language'] === $language);
                    $guideNames = array_values(array_unique(array_column($toursInLang, 'guide')));
                    return [
                        'language' => $language,
                        'names' => $guideNames
                    ];
                }, $languageNames));

                $schedules[] = [
                    'date' => date('l F j', strtotime($date)),
                    'location' => $firstTour['start_location'],
                    'seats_per_tour' => $firstTour['seats_per_tour'],
                    'prices' => [
                        'single' => $firstTour['single_price'],
                        'family' => $firstTour['family_price'],
                    ],
                    'guides' => $guides,
                ];
            }
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

    private function getUniqueTours(array $schedule): array
    {
        $tours = [];

        foreach ($schedule as $tour) {
            $key = $tour['start_location'] . '|' . $tour['seats_per_tour'] . '|' . $tour['family_price'] . '|' . $tour['single_price'];
            $tours[$key][] = $tour;
        }

        return $tours;
    }
}
