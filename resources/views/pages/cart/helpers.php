<?php

function formatTime($date)
{
    return date('H:i', strtotime($date));
}

function formatMoney($amount)
{
    return number_format($amount, 2);
}

function getScheduleDates(array $schedule): array
{
    $dates = array_map(function ($event) {
        return Carbon\Carbon::parse($event->event->start_date);
    }, $schedule);
    usort($dates, function ($a, $b) {
        return $a->timestamp - $b->timestamp;
    });

    return array_unique($dates);
}

function getScheduleByDate(array $schedule, string $date): array
{
    return array_filter($schedule, function ($event) use ($date) {
        if (!isset($event->event->start_date)) {
            return false;
        }

        $dateCarbon = Carbon\Carbon::parse($date);
        $startCarbon = Carbon\Carbon::parse($event->event->start_date);

        return $dateCarbon->eq($startCarbon);
    });
}
