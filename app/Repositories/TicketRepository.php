<?php

namespace App\Repositories;

use App\Models\Location;
use App\Models\TicketDance;
use App\Models\TicketYummy;
use App\Models\TicketHistory;
use App\Models\DTO\CompleteDanceEvent;
use App\Helpers\QueryBuilder;
use App\Models\EventHistory;
use App\Models\DTO\RestaurantInformation;

class TicketRepository extends Repository
{
    public function getDanceTickets(int $invoiceId): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $danceTickets = $queryBuilder
            ->table('dance_tickets')
            ->where('invoice_id', '=', $invoiceId)
            ->get();

        return array_map(fn($danceTicket) => new TicketDance($danceTicket), $danceTickets);
    }

    public function getYummyTickets(int $invoiceId): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $yummyTickets = $queryBuilder
            ->table('yummy_tickets')
            ->where('invoice_id', '=', $invoiceId)
            ->get();

        return array_map(fn($yummyTicket) => new TicketYummy($yummyTicket), $yummyTickets);
    }

    public function getHistoryTickets(int $invoiceId): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $historyTickets = $queryBuilder
            ->table('history_tickets')
            ->where('invoice_id', '=', $invoiceId)
            ->get();

        return array_map(fn($historyTicket) => new TicketHistory($historyTicket), $historyTickets);
    }
    public function getCompleteDanceEvent(int $eventId): ?CompleteDanceEvent
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $eventData = $queryBuilder
            ->table('dance_events')
            ->where('id', '=', (int)$eventId)
            ->first(); 

        if (!$eventData) {
            return null; 
        }

        $artists = $queryBuilder
            ->table('artists')
            ->where('id', '=', $eventData['artist_id'])
            ->get();

        $artistNames = array_map(fn($artist) => $artist['name'], $artists);

        $location = $queryBuilder
            ->table('locations')
            ->where('id', '=', $eventData['location_id'])
            ->first();
            
        if (!$location) {
            $locationData = [
                "id" => 1,
                "name" => "Error Finding location",
                "coordinates" => "3,3",
                "address" => "Error",
                "preview_description" => "leeg",
                "main_description" => "leeg"
            ];
            $locationObject = new Location($locationData);
        }
        else{
            $locationObject = new Location($location);

        }
    
        return new CompleteDanceEvent([
            'id' => $eventData['id'],
            'artists' => $artistNames,
            'location' => $locationObject,
            'total_tickets' => $eventData['total_tickets'],
            'session' => $eventData['session'],
            'price' => $eventData['price'],
            'vat' => $eventData['vat'],
            'start_time' => $eventData['start_time'],
            'start_date' => $eventData['start_date'],
            'end_time' => $eventData['end_time'],
            'end_date' => $eventData['end_date']
        ]);
        
    }
    public function getEventHistoryByEventId(int $eventId): ?EventHistory
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $eventHistoryRecord = $queryBuilder
            ->table('history_events')
            ->where('id', '=', $eventId)
            ->first();

        if (!$eventHistoryRecord) {
            return null;
        }
        return new EventHistory($eventHistoryRecord);
    }
    public function getRestaurantInformation(int $eventId): ?RestaurantInformation
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $eventData = $queryBuilder
            ->table('yummy_events')
            ->where('id', '=', $eventId)
            ->first(); 

        if (!$eventData) {
            return null; 
        }

        $restaurant = $queryBuilder
            ->table('restaurants')
            ->where('id', '=', $eventData['restaurant_id'])
            ->first();

        if (!$restaurant) {
            return null; 
        }

        $location = $queryBuilder
            ->table('locations')
            ->where('id', '=', $restaurant['location_id'])
            ->first();
        
        if (!$location) {
            $locationData = [
                "id" => 1,
                "name" => "Default Location",
                "coordinates" => "0,0",
                "address" => "Unknown Address",
                "preview_description" => "No description available",
                "main_description" => "No description available"
            ];
            $locationObject = new Location($locationData);
        } else {
            $locationObject = new Location($location);
        }

        return new RestaurantInformation([
            'event_id' => $eventData['id'],
            'kids_price' => $eventData['kids_price'],
            'adult_price' => $eventData['adult_price'],
            'vat' => $eventData['vat'],
            'start_time' => $eventData['start_time'],
            'start_date' => $eventData['start_date'],
            'restaurant_name' => $restaurant['name'],
            'location' => $locationObject,
        ]);
    }
}
