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

    /**
     * Parse the schedule for the dance page.
     *
     * Format:
     * $event['date']: Date events are held on
     * $event['rows']['event_id']: ID of event
     * $event['rows']['start']: Start time of event formatted as 00:00
     * $event['rows']['venue']: Location name
     * $event['rows']['artists']: Comma seperated list of artist names
     * $event['rows']['session']: Name of the session
     * $event['rows']['duration']: Duration of the session in minutes
     * $event['rows']['tickets_available']: How many tickets are still available
     * $event['rows']['price']: The price calculated with VAT (in SQL query)
     */
    public function getDanceSchedule(): array
    {
        $querySchedule = $this->danceRepository->getSchedule();
        $scheduleList = [];

        $eventDates = $this->getScheduleDates($querySchedule);

        foreach ($eventDates as $date) {
            $eventsOnDate = $this->getScheduleByDate($querySchedule, $date);

            $dailySchedule = [
                'date' => date('l F j', strtotime($date)),
                'rows' => [],
            ];

            foreach ($eventsOnDate as $event) {
                $dailySchedule['rows'][] = [
                    'event_id' => $event['event_id'],
                    'start' => date('H:i', strtotime($event['start_time'])),
                    'venue' => $event['location_name'],
                    'artists' => explode(', ', $event['artist_names']),
                    'session' => $this->getSessionName($event['session']),
                    'duration' => $event['duration'],
                    'tickets_available' => $event['tickets_available'],
                    'price' => $event['price'],
                ];
            }

            $scheduleList[] = $dailySchedule;
        }

        return $scheduleList;
    }

    private function getSessionName(string $session): string
    {
        return match (DanceSessionEnum::from($session)) {
            DanceSessionEnum::CLUB => 'Club',
            DanceSessionEnum::B2B => 'Back2Back',
            DanceSessionEnum::TIESTO_WORLD => 'Tiesto World',
        };
    }

    /**
     * Parse the schedule for the history page.
     *
     * Format:
     * $event['date']: Date events are held on
     * $event['location']: The location the event is held at
     * $event['seats_per_tour']: Number of seats per tour
     * $event['prices']['single']: Price of a single ticket with VAT (in SQL query)
     * $event['prices']['family']: Price of a family ticket with VAT (in SQL query)
     * $event['guides'][]['language']: The language of the guide
     * $event['guides'][]['names'][]: Array of guide names
     * $event['start'][]['time']: The start time of the tour formatted as 00:00
     * $event['start'][]['tours'][]: Array of tours, key is language, value is tour ID
     */
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

                $guides = array_map(function ($language) use ($tours) {
                    $toursInLang = array_filter($tours, fn ($tour) => $tour['language'] === $language);
                    $guideNames = array_values(array_unique(array_column($toursInLang, 'guide')));

                    return [
                        'language' => $language,
                        'names' => $guideNames,
                    ];
                }, $languageNames);

                $startTimes = array_values(array_unique(array_column($tours, 'start_time')));
                sort($startTimes);

                $start = array_map(function ($time) use ($tours) {
                    $toursAtTime = array_filter($tours, fn ($tour) => $tour['start_time'] === $time);
                    $toursGroupedByLanguage = [];

                    foreach ($toursAtTime as $tour) {
                        $toursGroupedByLanguage[$tour['language']][] = $tour['tour_id'];
                    }

                    return [
                        'time' => date('H:i', strtotime($time)),
                        'tours' => $toursGroupedByLanguage,
                    ];
                }, $startTimes);

                $schedules[] = [
                    'date' => date('l F j', strtotime($date)),
                    'location' => $firstTour['start_location'],
                    'seats_per_tour' => $firstTour['seats_per_tour'],
                    'prices' => [
                        'single' => $firstTour['single_price'],
                        'family' => $firstTour['family_price'],
                    ],
                    'guides' => $guides,
                    'start' => $start,
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

    /**
     * Get unique tours by start location, seats per tour, family price and single price.
     * This is a simple way to ensure that tours with a single different field are split off.
     */
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
