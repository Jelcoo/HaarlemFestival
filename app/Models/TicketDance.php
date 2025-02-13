<?php

namespace App\Models;

use App\Enum\InvoiceStatusEnum;
use Carbon\Carbon;

class TicketDance extends Ticket
{
    public int $id;
    public int $ticket_id;
    public int $event_id;
    public bool $all_access;

    public function __construct(array $collection)
    {
        $this->id = $collection["id"];
        $this->ticket_id = $collection["ticket_id"];
        $this->event_id = $collection["event_id"];
        $this->all_access = $collection["all_access"];
    }
}
