<?php

namespace App\Models;

use App\Enum\InvoiceStatusEnum;
use Carbon\Carbon;

class TicketYummy extends Ticket
{
    public int $id;
    public int $ticket_id;
    public int $event_id;
    public int $kids_count;
    public int $adult_count;

    public function __construct(array $collection)
    {
        $this->id = $collection["id"];
        $this->ticket_id = $collection["ticket_id"];
        $this->event_id = $collection["event_id"];
        $this->kids_count = $collection["kids_count"];
        $this->adult_count = $collection["adult_count"];
    }
}
