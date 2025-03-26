<?php

if (empty($completeDanceEvents) && empty($completeHistoryEvents) && empty($completeRestaurantEvents)) {
    echo "<h1 class='text-center text-danger my-5'>You didn’t buy any tickets yet</h1>";
    return;
}

$listDates = array();

foreach ($completeDanceEvents as $danceItem) {
    $eventDateStr = $danceItem['event']->start_date->format('Y-m-d');
    if ($eventDateStr && !in_array($eventDateStr, $listDates)) {
        $listDates[] = $eventDateStr;
    }
}

foreach ($completeHistoryEvents as $historyItem) {
    $eventDateStr = $historyItem['event']->start_date->format('Y-m-d');
    if ($eventDateStr && !in_array($eventDateStr, $listDates)) {
        $listDates[] = $eventDateStr;
    }
}

foreach ($completeRestaurantEvents as $reservation) {
    $eventDateStr = $reservation['event']->start_date;
    if ($eventDateStr) {
        $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $eventDateStr);
        if ($datetime) {
            $stringDate = $datetime->format('Y-m-d');
            if (!in_array($stringDate, $listDates)) {
                $listDates[] = $stringDate;
            }
        }
    }
}

sort($listDates);

$danceProgram = $completeDanceEvents;
$historyProgram = $completeHistoryEvents;
$restaurantProgram = $completeRestaurantEvents;

foreach ($listDates as $date) {
    $dateObject = DateTime::createFromFormat('Y-m-d', $date);
    if (!$dateObject) {
        continue;
    }
    $formattedDate = $dateObject->format('l, F jS');
    echo "<div class='my-4'><h3 class='text-dark'>$formattedDate</h3></div>";

    $dayDanceprogram = [];
    $dayHistoryprogram = [];
    $dayRestaurantprogram = [];

    foreach ($danceProgram as $danceItem) {
        $eventDateStr = $danceItem['event']->start_date->format('Y-m-d');
        if ($eventDateStr == $date) {
            $dayDanceprogram[] = $danceItem;
        }
    }

    foreach ($historyProgram as $historyItem) {
        $eventDateStr = $historyItem['event']->start_date->format('Y-m-d');
        if ($eventDateStr == $date) {
            $dayHistoryprogram[] = $historyItem;
        }
    }

    foreach ($restaurantProgram as $reservation) {
        $eventDateStr = $reservation['event']->start_date;
        if ($date == $eventDateStr) {
            $dayRestaurantprogram[] = $reservation;
        }
    }

    if (!empty($dayDanceprogram)) {
        echo "<h4 class='mt-3'>Dance Events:</h4>";
        echo "<table class='table table-bordered table-striped'>
                <thead class='table-dark'>
                    <tr>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Location</th>
                        <th>Artist</th>
                    </tr>
                </thead>
                <tbody>";

        foreach ($dayDanceprogram as $eventData) {
            if (isset($eventData["event"]->artists)) {
                $startTime = isset($eventData['event']->start_time) && $eventData['event']->start_time instanceof DateTime
                    ? $eventData['event']->start_time->format('H:i')
                    : 'Unknown Time';
                $endTime = isset($eventData['event']->end_time) && $eventData['event']->end_time instanceof DateTime
                    ? $eventData['event']->end_time->format('H:i')
                    : 'Unknown Time';
                $location = $eventData["event"]->location->name ?? 'Unknown Location';
                
                $artists = '';
                if (is_array($eventData["event"]->artists)) {
                    $artists = implode(', ', $eventData["event"]->artists);
                } elseif (is_string($eventData["event"]->artists)) {
                    $artists = $eventData["event"]->artists;
                }

                echo "<tr>
                        <td>$startTime</td>
                        <td>$endTime</td>
                        <td>$location</td>
                        <td>$artists</td>
                      </tr>";
            }
        }
        echo "</tbody></table>";
    }

    if (!empty($dayHistoryprogram)) {
        echo "<h4 class='mt-4'>History Events:</h4>";
        echo "<table class='table table-bordered table-striped'>
                <thead class='table-dark'>
                    <tr>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Guide</th>
                        <th>Language</th>
                    </tr>
                </thead>
                <tbody>";

        foreach ($dayHistoryprogram as $event) {
            $startTime = isset($event['event']->start_time) && $event['event']->start_time instanceof DateTime
                ? $event['event']->start_time->format('H:i')
                : 'Unknown Time';
            $endTime = isset($event['event']->end_time) && $event['event']->end_time instanceof DateTime
                ? $event['event']->end_time->format('H:i')
                : 'Unknown Time';

            $guide = isset($event['event']->guide) ? $event['event']->guide : 'Unknown Guide';
            $language = isset($event['event']->language) ? $event['event']->language : 'Unknown Language';

            echo "<tr>
                    <td>$startTime</td>
                    <td>$endTime</td>
                    <td>$guide</td>
                    <td>$language</td>
                </tr>";
        }

        echo "</tbody></table>";
    }

    if (!empty($dayRestaurantprogram)) {
        echo "<h4 class='mt-4'>Restaurant Events:</h4>";
        echo "<table class='table table-bordered table-striped'>
                <thead class='table-dark'>
                    <tr>
                        <th>Start Time</th>
                        <th>Restaurant Name</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>";

        foreach ($dayRestaurantprogram as $event) {
            $startTime = $event['event']->start_time;
            $formattedTime = date("H:i", strtotime($startTime));

            $restaurantName = isset($event['event']->restaurant_name) ? $event['event']->restaurant_name : 'Unknown Restaurant';
            $location = isset($event['event']->location) ? $event['event']->location->address : 'Unknown Location';

            echo "<tr>
                    <td>$formattedTime</td>
                    <td>$restaurantName</td>
                    <td>$location</td>
                </tr>";
        }

        echo "</tbody></table>";
    }
}
?>
