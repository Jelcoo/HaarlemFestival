<?php

namespace App\Repositories;

use App\Models\Location;
use App\Enum\EventTypeEnum;
use App\Models\TicketDance;
use App\Models\TicketYummy;
use App\Models\EventHistory;
use App\Helpers\QueryBuilder;
use App\Models\TicketHistory;
use App\Models\DTO\CompleteDanceEvent;
use App\Models\DTO\RestaurantInformation;

class TicketRepository extends Repository
{
    public function checkDanceTicketFromEvent(int $eventId, string $ticketId): bool
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
    
        $danceTicket = $queryBuilder
            ->table('dance_tickets')
            ->where('dance_event_id', '=', $eventId)
            ->where('qrcode', '=', $ticketId)
            ->where('ticket_used', '=', false)
            ->first();
    
        return !empty($danceTicket);
    }
    public function checkAllAccesTicketFromEvent(int $eventId, string $ticketId): bool
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
    
        $danceTicket = $queryBuilder
            ->table('dance_tickets')
            ->where('dance_event_id', '=', $eventId)
            ->where('qrcode', '=', $ticketId)
            ->where('ticket_used', '=', false)
            ->first();
    
        return !empty($danceTicket);
    }
    
    
    public function checkHistoryTicketFromEvent(int $eventId, string $ticketId): bool
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $historyTicket = $queryBuilder
            ->table('history_tickets')
            ->where('history_event_id', '=', $eventId)
            ->where('ticket_used', '=', false)
            ->first();

        return !empty($historyTicket); 
    }

    public function checkYummyTicketFromEvent(int $eventId, string $ticketId): bool
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $yummyTicket = $queryBuilder
            ->table('yummy_tickets')
            ->where('yummy_event_id', '=', $eventId)
            ->where('ticket_used', '=', false)
            ->first();
        var_dump($yummyTicket);
        return !empty($yummyTicket); 
    }

    public function getDanceTickets(int $invoiceId): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $danceTickets = $queryBuilder
            ->table('dance_tickets')
            ->where('invoice_id', '=', $invoiceId)
            ->get();
        return array_map(fn ($danceTicket) => new TicketDance($danceTicket), $danceTickets);
    }

    public function getYummyTickets(int $invoiceId): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $yummyTickets = $queryBuilder
            ->table('yummy_tickets')
            ->where('invoice_id', '=', $invoiceId)
            ->get();

        return array_map(fn ($yummyTicket) => new TicketYummy($yummyTicket), $yummyTickets);
    }

    public function getHistoryTickets(int $invoiceId): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $historyTickets = $queryBuilder
            ->table('history_tickets')
            ->where('invoice_id', '=', $invoiceId)
            ->get();

        return array_map(fn ($historyTicket) => new TicketHistory($historyTicket), $historyTickets);
    }

    public function getCompleteDanceEvent(int $eventId): ?CompleteDanceEvent
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $eventData = $queryBuilder
            ->table('dance_events')
            ->where('id', '=', (int) $eventId)
            ->first();

        if (!$eventData) {
            return null;
        }

        $query = $this->getConnection()->prepare('
        SELECT a.id, a.name, a.preview_description, a.main_description, a.iconic_albums
        FROM artists a
        JOIN dance_event_artists dea ON a.id = dea.artist_id
        WHERE dea.event_id = :eventId;');

        $query->bindValue(':eventId', $eventId, \PDO::PARAM_INT);
        $query->execute();
        $queryArtists = $query->fetchAll();

        $artistNames = array_map(fn ($artist) => $artist['name'], $queryArtists);

        $location = $queryBuilder
            ->table('locations')
            ->where('id', '=', $eventData['location_id'])
            ->first();

        if (!$location) {
            $locationData = [
                'id' => 1,
                'name' => 'Error Finding location',
                'event_type' => EventTypeEnum::UNKNOWN->value,
                'coordinates' => '3,3',
                'address' => 'Error',
                'preview_description' => 'leeg',
                'main_description' => 'leeg',
            ];
            $locationObject = new Location($locationData);
        } else {
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
            'end_date' => $eventData['end_date'],
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
                'id' => 1,
                'name' => 'Default Location',
                'event_type' => EventTypeEnum::UNKNOWN->value,
                'coordinates' => '0,0',
                'address' => 'Unknown Address',
                'preview_description' => 'No description available',
                'main_description' => 'No description available',
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
            'restaurant_name' => $locationObject->name,
            'location' => $locationObject,
        ]);
    }
    public function useRestaurantTicket($id):bool{
        $query = "SELECT ticket_used FROM yummy_tickets WHERE id = $id";
        $ticket = $this->getConnection()->query($query)->fetch();

        if ($ticket) {
            if (!$ticket['ticket_used']) {
                $updateQuery = "UPDATE yummy_tickets SET ticket_used = TRUE WHERE id = $id";
                $this->getConnection()->query($updateQuery);
                return true; 
            } else {
                return false; 
            }
        }
        return false; 
    }
    public function useHistoryTicket($id):bool{
        $query = "SELECT ticket_used FROM history_tickets WHERE id = $id";
        $ticket = $this->getConnection()->query($query)->fetch();
    
        if ($ticket) {
            if (!$ticket['ticket_used']) {
                $updateQuery = "UPDATE history_tickets SET ticket_used = TRUE WHERE id = $id";
                $this->getConnection()->query($updateQuery);
                return true; 
            } else {
                return false;
            }
        }
        return false; 
    }
    public function useDanceTicket($id, $eventId): bool {
        $conn = $this->getConnection();
        $query = "SELECT ticket_used, all_access FROM dance_tickets WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$id]);
        $ticket = $stmt->fetch();
    
        if ($ticket) {
            if (!$ticket['ticket_used']) {
                if ($ticket['all_access'] !== 'false') {
                    $insertQuery = "INSERT INTO all_access_used (ticket_id, dance_event_id) VALUES (?, ?)";
                    $insertStmt = $conn->prepare($insertQuery);
                    $insertStmt->execute([$id, $eventId]);
                    return true;
                } else {
                    $updateQuery = "UPDATE dance_tickets SET ticket_used = TRUE WHERE id = ?";
                    $updateStmt = $conn->prepare($updateQuery);
                    $updateStmt->execute([$id]);
                    return true;
                }
            }
        }
    
        return false;
    }
    
    public function useAllAccesTicket($ticketId, $eventId):bool
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $ticket = $queryBuilder->table('dance_tickets')->where('id', $ticketId)->first();
        if (!$ticket) {
            return false;
        }

        $event = $queryBuilder->table('dance_events')->where('id', $eventId)->first();
        if (!$event) {
            return false;
        }

        $existingEntry = $queryBuilder->table('all_access_used')
            ->where('ticket_id', $ticketId)
            ->where('dance_event_id', $eventId)
            ->first();

        if ($existingEntry) {
            return false;
        }

        $queryBuilder->table('all_access_used')->insert([
            'ticket_id' => $ticketId,
            'dance_event_id' => $eventId
        ]);
        return true;
    }
    public function checkifAllAcces($id): bool {
        $query = "SELECT all_access FROM dance_tickets WHERE id = $id";
        $ticket = $this->getConnection()->query($query)->fetch();
        if ($ticket) {
            return $ticket['all_access'] !== 'false';
        }
        return false; 
    }
    public function checkValidityAllAcces(int $ticketId, int $eventId){
        /* true is used because the question is is used */ 
        $queryBuilder = new QueryBuilder($this->getConnection());

        $ticket = $queryBuilder
            ->table('dance_tickets')
            ->where('id', '=', $ticketId)
            ->first();

        if (!$ticket) {
            return true;
        }
        if ($ticket['ticket_used'] == true) {
            return true;
        }    
        $event = $queryBuilder
            ->table('dance_events')
            ->where('id', '=', $eventId)
            ->first();

        if (!$event) {
            return true;
        }
        if ($ticket['all_access'] === 'false') {
            var_dump('hi');
            return true;
        }

        if ($ticket['all_access'] === 'all' || ($ticket['all_access'] == $event['start_date'])) {
            return false;
        }
        var_dump('hi');
        return true;
        }
}
