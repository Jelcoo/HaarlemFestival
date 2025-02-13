<?php

namespace App\Models;

use App\Enum\InvoiceStatusEnum;
use Carbon\Carbon;

class TicketHistory extends Ticket
{
    public int $id;
    public int $ticket_id;
    public int $event_id;
    public int $total_seats;
    public bool $family_ticket;

    public function __construct(array $collection)
    {
        $this->id = $collection["id"];
        $this->ticket_id = $collection["ticket_id"];
        $this->event_id = $collection["event_id"];
        $this->total_seats = $collection["total_seats"];
        $this->family_ticket = $collection["family_ticket"];
    }
}
