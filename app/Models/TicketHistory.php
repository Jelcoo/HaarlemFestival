<?php

namespace App\Models;

class TicketHistory extends Ticket
{
    public int $history_event_id;
    public int $total_seats;
    public bool $family_ticket;

    public function __construct(array $collection)
    {
        parent::__construct($collection);

        $this->history_event_id = $collection['history_event_id'];
        $this->total_seats = $collection['total_seats'];
        $this->family_ticket = $collection['family_ticket'];
    }
}
